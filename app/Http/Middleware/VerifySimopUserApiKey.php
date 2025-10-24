<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class VerifySimopUserApiKey
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->bearerToken();

        if (!$apiKey) {
            return response()->json([
                'status' => 'error',
                'message' => 'API Key de utilizador não fornecida.'
            ], 401);
        }

        $user = User::where('api_key', $apiKey)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'API Key de utilizador inválida.'
            ], 401);
        }

        $request->merge(['user' => $user]);
        return $next($request);
    }
}
