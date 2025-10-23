<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;

class AuthTokenController extends Controller
{
    public function getToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status' => 'error', 'message' => 'Credenciais inválidas.'], 401);
        }

        if (!$user->email_verified_at) {
            return response()->json(['status' => 'error', 'message' => 'Conta não verificada.'], 403);
        }

        if (!$user->api_key) {
            $user->api_key = Str::uuid();
            $user->save();
        }

        return response()->json([
            'status' => 'success',
            'api_key' => $user->api_key,
        ]);
    }
}
