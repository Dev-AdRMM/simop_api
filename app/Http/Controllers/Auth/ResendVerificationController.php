<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Carbon\Carbon;
use App\Models\User;
use App\Models\UserVerification;
use App\Mail\VerifyUserMail;

class ResendVerificationController extends Controller
{
    /**
     * Reenvia um novo código de verificação para o email do usuário
     */
    public function resendCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // 🔹 Busca o usuário
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Usuário não encontrado.'
            ], 404);
        }

        // 🔹 Se já estiver verificado, não precisa reenviar
        if ($user->email_verified_at) {
            return response()->json([
                'status' => 'info',
                'message' => 'Este email já foi verificado.'
            ], 200);
        }

        // 🔹 Apaga códigos antigos
        UserVerification::where('user_id', $user->id)->delete();

        // 🔹 Cria novo código
        $code = random_int(100000, 999999);

        UserVerification::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(15),
        ]);

        # Envia o email novamente
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
            'message' => 'Novo código de verificação enviado para o seu email.'
        ]);
    }
}
