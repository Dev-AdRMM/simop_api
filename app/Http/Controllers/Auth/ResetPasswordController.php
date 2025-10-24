<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Carbon\Carbon;
use App\Models\User;
use App\Models\PasswordReset;
use App\Mail\ResetPasswordCodeMail;

class ResetPasswordController extends Controller
{
    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        $code = random_int(100000, 999999);

        PasswordReset::updateOrCreate(
            ['email' => $user->email],
            ['code' => $code, 'expires_at' => Carbon::now()->addMinutes(15)]
        );

        # Envia o e-mail com o código
        try {
            Mail::to($user->email)->send(new ResetPasswordMail($code));
        } catch (TransportExceptionInterface $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Falha ao enviar email: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Código de redefinição enviado para o seu e-mail.',
        ]);
    }
}
