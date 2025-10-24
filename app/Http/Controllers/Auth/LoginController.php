<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class LoginController extends Controller
{
     # Faz login e retorna um token de sessão
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        # Verifica se existe
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Credenciais inválidas.'
            ], 401);
        }

        # Confere senha
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Credenciais inválidas.'
            ], 401);
        }

        # Verifica se a conta está validada
        if (!$user->email_verified_at) {
            return response()->json([
                'status' => 'error',
                'message' => 'Conta ainda não verificada. Verifique o seu email.'
            ], 403);
        }

        # Gera token de sessão (válido até logout)
        $token = Str::random(60);

        # Pode salvar no campo "remember_token" ou numa tabela separada
        $user->remember_token = hash('sha256', $token);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Login efetuado com sucesso.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'api_key' => $user->api_key, // chave de API permanente
                'session_token' => $token,   // token de sessão
            ]
        ]);
    }

    # Faz logout e invalida o token
    public function logout(Request $request)
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

        $user->remember_token = null;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Sessão encerrada com sucesso.'
        ]);
    }
}
