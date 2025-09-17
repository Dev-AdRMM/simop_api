<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Services\PaymentGateways\MkeshPaymentService;
use App\Traits\ApiLogsTransactions;
use App\Models\Transaction;

class MkeshPaymentController extends Controller
{
    use ApiLogsTransactions;

    protected $mkesh;

    public function __construct(MkeshPaymentService $mkesh)
    {
        $this->mkesh = $mkesh;
    }

    /**
     * Testa um débito
     */
    public function debit(Request $request)
    {
        $request->validate([
            'msisdn' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'transaction_id' => 'required|string',
        ]);

        $response = $this->mkesh->debitRequest(
            $request->msisdn,
            $request->amount,
            $request->transaction_id
        );

        // 🔹 Usa Trait
        $this->logApi(
            'mkesh',
            '/api/v1/mkesh/debit',
            $request->method(),
            $request->headers->all(),
            $request->all(),
            $response,
            'SENT',
            $request->transaction_id,
            $request->msisdn,
            $request->amount
        );

        return response($response, 200)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Consulta status de uma transação
     */

    // public function status(Request $request)
    // {
    //     $request->validate([
    //         'transaction_id' => 'required|string',
    //     ]);

    //     $response = $this->mkesh->getTransactionStatus($request->transaction_id);

    //     // 🔹 Tenta interpretar XML
    //     $xml = simplexml_load_string($response);
    //     $providerStatus = null;

    //     if ($xml && isset($xml->status)) {
    //         // Caso sucesso ou falha normal
    //         $providerStatus = strtolower((string) $xml->status);
    //     } elseif ($xml && isset($xml['errorcode'])) {
    //         // 🔹 Caso de erro do provedor
    //         $errorCode = (string) $xml['errorcode'];

    //         // Loga, mas não salva nada no banco
    //         Log::error("[mkesh] Erro na consulta de transação", [
    //             'transaction_id' => $request->transaction_id,
    //             'error_code'     => $errorCode,
    //             'response'       => $response,
    //         ]);

    //         // Retorna o XML de erro diretamente para o cliente
    //         return response($response, 200)
    //             ->header('Content-Type', 'application/xml');
    //     }

    //     // 🔹 Atualiza a transação só se existir localmente
    //     $transaction = Transaction::where('transaction_id', $request->transaction_id)->first();

    //     if ($transaction) {
    //         $transaction->update([
    //             'provider_response' => $response,
    //             'status'            => $providerStatus ?? 'checked',
    //         ]);
    //     } else {
    //         Log::warning("Consulta de transação inexistente no banco/local", [
    //             'transaction_id' => $request->transaction_id,
    //             'response'       => $response,
    //         ]);
    //     }

    //     // 🔹 Log no trait (sem tentar criar quando msisdn/amount não existem)
    //     $this->logApi(
    //         'mkesh',
    //         '/api/v1/mkesh/status',
    //         $request->method(),
    //         $request->headers->all(),
    //         $request->all(),
    //         $response,
    //         $providerStatus ?? 'CHECKED',
    //         $request->transaction_id
    //     );

    //     return response($response, 200)
    //         ->header('Content-Type', 'application/xml');
    // }

    
    public function status(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|string',
        ]);

        $response = $this->mkesh->getTransactionStatus($request->transaction_id);

        // 🔹 Tenta interpretar XML
        $xml = simplexml_load_string($response);
        $providerStatus = null;

        if ($xml && isset($xml->status)) {
            $providerStatus = strtolower((string) $xml->status);
        } elseif ($xml && isset($xml['errorcode'])) {
            // 🔹 Caso de erro do provedor
            $errorCode = (string) $xml['errorcode'];

            Log::error("[mkesh] Erro na consulta de transação", [
                'transaction_id' => $request->transaction_id,
                'error_code'     => $errorCode,
                'response'       => $response,
            ]);

            // 🔹 Decide formato da resposta
            if ($request->wantsJson()) {
                return response()->json([
                    'transaction_id' => $request->transaction_id,
                    'status'         => 'error',
                    'error_code'     => $errorCode,
                    'message'        => 'Transação não encontrada no provedor'
                ], 404);
            }

            // Default → retorna XML original do provedor
            return response($response, 404)
                ->header('Content-Type', 'application/xml');
        }

        // 🔹 Atualiza a transação só se existir localmente
        $transaction = Transaction::where('transaction_id', $request->transaction_id)->first();

        if ($transaction) {
            $transaction->update([
                'provider_response' => $response,
                'status'            => $providerStatus ?? 'checked',
            ]);
        } else {
            Log::warning("Consulta de transação inexistente no banco/local", [
                'transaction_id' => $request->transaction_id,
                'response'       => $response,
            ]);
        }

        // 🔹 Log no trait
        $this->logApi(
            'mkesh',
            '/api/v1/mkesh/status',
            $request->method(),
            $request->headers->all(),
            $request->all(),
            $response,
            $providerStatus ?? 'CHECKED',
            $request->transaction_id
        );

        // 🔹 Decide resposta final
        if ($request->wantsJson()) {
            return response()->json([
                'transaction_id' => $request->transaction_id,
                'status'         => $providerStatus ?? 'checked',
                'response'       => $response,
            ], 200);
        }

        return response($response, 200)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Endpoint de Callback (recebe XML)
     */
    public function callback(Request $request)
    {
        $xmlContent = $request->getContent();
        Log::info("Callback Mkesh recebido", ['xml' => $xmlContent]);

        // Parse XML
        $xml = simplexml_load_string($xmlContent);
        $transactionId = (string) $xml->transactionid ?? null;
        $externalTransactionId = (string) $xml->externaltransactionid ?? null;
        $status = (string) $xml->status ?? 'RECEIVED';

        // Atualiza transação existente
        if ($externalTransactionId) {
            Transaction::where('transaction_id', $externalTransactionId)
                ->update([
                    'status' => strtolower($status),
                    'provider_response' => $xmlContent,
                ]);
        }

        // Salva log (com IDs corretos)
        $this->logApi(
            'mkesh',
            '/api/v1/mkesh/callback',
            $request->method(),
            $request->headers->all(),
            $xmlContent,
            '<response>ACKNOWLEDGED</response>',
            $status,
            $externalTransactionId
        );

        $response = <<<XML
            <?xml version="1.0" encoding="UTF-8"?>
            <response>
            <status>ACKNOWLEDGED</status>
            </response>
            XML;

        return response($response, 200)
            ->header('Content-Type', 'application/xml');
    }
}
