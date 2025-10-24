<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserVerification;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\VerifyUserMail;

class VerificationController extends Controller
{
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|numeric',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Usuário não encontrado.'], 404);
        }

        $verification = UserVerification::where('user_id', $user->id)
            ->where('code', $request->code)
            ->first();

        if (!$verification || $verification->expires_at->isPast()) {
            return response()->json(['status' => 'error', 'message' => 'Código inválido ou expirado.'], 400);
        }

        // 🔒 Verifica e gera API Key
        \DB::transaction(function () use ($user, $verification) {
            $user->email_verified_at = now();
            $user->api_key = Str::uuid();
            $user->save();
            $verification->delete();
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Conta verificada com sucesso!',
            'api_key' => $user->api_key,
        ]);
    }

    # Reenviar código de verificação por email
    public function resendCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Usuário não encontrado.'
            ], 404);
        }

        if ($user->email_verified_at) {
            return response()->json([
                'status' => 'error',
                'message' => 'Esta conta já foi verificada.'
            ], 400);
        }
        # Apaga códigos antigos
        UserVerification::where('user_id', $user->id)->delete();

        $code = random_int(100000, 999999);

        UserVerification::updateOrCreate(
            ['user_id' => $user->id],
            ['code' => $code, 
            'expires_at' => Carbon::now()->addMinutes(15)]
        );

        # Envia o e-mail com o código
        try {
            Mail::to($user->email)->send(new VerifyUserMail($code));
        } catch (TransportExceptionInterface $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Falha ao enviar email: ' . $e->getMessage(),
            ], 500);
        } 

        return response()->json([
            'status' => 'success',
            'message' => 'Novo código de verificação enviado para o e-mail.'
        ]);
    }
}
