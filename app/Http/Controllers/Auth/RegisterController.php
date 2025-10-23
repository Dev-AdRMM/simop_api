<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\User;
use App\Models\UserVerification;
use App\Mail\VerifyUserMail;

class RegisterController extends Controller
{
    public function registerUser(Request $request)
    {
        // Validação completa (inclui confirmação de password)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed', // <-- usa password_confirmation automaticamente
        ]);

        // Criação do utilizador
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'api_key' => null,
        ]);

        // Gera código de verificação e salva na tabela user_verifications
        $code = random_int(100000, 999999);

        UserVerification::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(15),
        ]);

        // Envia o e-mail com o código
        Mail::to($user->email)->send(new VerifyUserMail($code));

        // Retorna resposta  (não expõe password)
        return response()->json([
            'status' => 'success',
            'message' => 'Utilizador registado com sucesso. Um código de verificação foi enviado para o seu email.',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ],
        ], 201);
    }
}
