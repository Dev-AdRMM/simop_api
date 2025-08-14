<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class MkeshCallbackController extends Controller
{
    public function handle(Request $request)
    {
        // Verifica autenticação BASIC
        $user = $request->getUser();
        $pass = $request->getPassword();

        if ($user !== 'ADR' || $pass !== 'Mozambique2025!') {
            return response('Unauthorized', Response::HTTP_UNAUTHORIZED)
                ->header('WWW-Authenticate', 'Basic realm="mKesh API"');
        }

        // Captura o XML enviado
        $xmlContent = $request->getContent();

        // Log para debug
        Log::info('mKesh Callback recebido:', ['xml' => $xmlContent]);

        // Aqui você pode converter o XML para array se quiser processar
        try {
            $xmlObject = simplexml_load_string($xmlContent);
            $data = json_decode(json_encode($xmlObject), true);
            Log::info('mKesh Callback parsed:', $data);
        } catch (\Exception $e) {
            Log::error('Erro ao parsear XML do mKesh: ' . $e->getMessage());
        }

        // Retorna confirmação em XML
        $responseXml = '<?xml version="1.0" encoding="UTF-8"?>
                        <response>
                            <status>RECEIVED</status>
                        </response>';

            return response($responseXml, 200)
                ->header('Content-Type', 'application/xml');
    }
}
