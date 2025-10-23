<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserVerification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class VerificationController extends Controller
{
    public function verify(Request $request)
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

        DB::transaction(function () use ($user, $verification) {
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
}
