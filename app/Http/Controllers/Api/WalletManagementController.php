<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\Wallet;

class WalletManagementController extends Controller
{
    #Lista todas as carteiras do sistema (ou de um cliente específico)
    public function index(Request $request)
    {
        $query = Wallet::query();

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('provider')) {
            $query->where('provider', $request->provider);
        }

        $wallets = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $wallets,
        ], 200);
    }

    #Cria uma nova carteira
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'name' => 'required|string|unique:wallets,name',
            'provider' => ['required', Rule::in(['mpesa', 'mkesh', 'emola'])],
            'callback_url' => 'nullable|url',
            'settings' => 'required|array',
        ]);

        $provider = strtolower($request->provider);
        $settings = $request->settings;

        // 🔍 Validações específicas por provedor
        switch ($provider) {
            case 'mpesa':
                $request->validate([
                    'settings.api_key' => 'required|string',
                    'settings.public_key' => 'required|string',
                    'settings.service_provider_code' => 'required|string',
                ]);
                break;

            case 'mkesh':
                $request->validate([
                    'settings.username' => 'required|string',
                    'settings.password' => 'required|string',
                ]);
                break;

            case 'emola':
                $request->validate([
                    'settings.emola_key' => 'required|string',
                    'settings.username' => 'required|string',
                    'settings.password' => 'required|string',
                    'settings.service_provider_code' => 'required|string',
                    'settings.api_host' => 'required|url',
                ]);
                break;
        }

        // 💾 Criação da carteira
        $wallet = Wallet::create([
            'user_id' => $request->user_id,
            'name' => strtoupper($request->name),
            'provider' => $provider,
            'callback_url' => $request->callback_url,
            'settings' => $settings,
            'api_key' => (string) Str::uuid(),
            'status' => 'active',
            'active' => true,
            'balance' => 0.00,
        ]);

        Log::info("[SIMOP] Carteira criada", ['wallet' => $wallet]);

        return response()->json([
            'success' => true,
            'message' => 'Carteira criada com sucesso.',
            'data' => $wallet,
        ], 201);

        // 4️⃣ Retorno em formato padronizado
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Carteira criada com sucesso.',
        //     'data' => [
        //         'id' => $wallet->id,
        //         'name' => $wallet->name,
        //         'provider' => $wallet->provider,
        //         'api_key' => $wallet->api_key,
        //         'callback_url' => $wallet->callback_url,
        //         'balance' => $wallet->balance,
        //         'status' => $wallet->status,
        //     ]
        // ], 201);
    }
    #Detalhes de uma carteira
    public function show(Wallet $wallet)
    {
        return response()->json([
            'success' => true,
            'data' => $wallet,
        ], 200);
    }

    #Atualiza informações de uma carteira
    public function update(Request $request, Wallet $wallet)
    {
        $request->validate([
            'callback_url' => 'nullable|url',
            'settings' => 'nullable|array',
            'status' => ['nullable', Rule::in(['active', 'inactive', 'suspended'])],
        ]);

        $wallet->update($request->only('callback_url', 'settings', 'status'));

        Log::info("[SIMOP] Carteira atualizada", ['wallet' => $wallet]);

        return response()->json([
            'success' => true,
            'message' => 'Carteira atualizada com sucesso.',
            'data' => $wallet,
        ], 200);
    }

    #Suspende uma carteira
    public function suspend(Request $request, Wallet $wallet)
    {
        $request->validate([
            'reason' => 'nullable|string|max:255',
        ]);

        $wallet->suspend($request->reason ?? 'Suspensão administrativa');

        return response()->json([
            'success' => true,
            'message' => 'Carteira suspensa com sucesso.',
            'data' => $wallet,
        ], 200);
    }

    #Reativa uma carteira
    public function activate(Wallet $wallet)
    {
        $wallet->activate();

        return response()->json([
            'success' => true,
            'message' => 'Carteira reativada com sucesso.',
            'data' => $wallet,
        ], 200);
    }

    #Elimina (soft delete) uma carteira
    public function destroy(Wallet $wallet)
    {
        $wallet->delete();

        return response()->json([
            'success' => true,
            'message' => 'Carteira removida com sucesso.',
        ], 200);
    }
}
