<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Wallet;

class VerifySimopApiKey
{
     /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next)
    {
        // Extrai o token do cabeçalho Authorization: Bearer {token}
        $apiKey = $request->bearerToken();

        if (!$apiKey) {
            return response()->json([
                'status' => 'error',
                'message' => 'API Key não fornecida. Use Authorization: Bearer {api_key}.'
            ], 401);
        }

        // Verifica se existe uma carteira ativa com esta API Key
        $wallet = Wallet::where('api_key', $apiKey)
            ->where('active', true)
            ->first();

        if (!$wallet) {
            return response()->json([
                'status' => 'error',
                'message' => 'API Key inválida ou carteira inativa.'
            ], 401);
        }

        // Anexa a carteira autenticada ao request
        $request->merge(['wallet' => $wallet]);

        return $next($request);
    }
}
