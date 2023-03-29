<?php

namespace App\Http\Services\SistemaTramites;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SistemaTramitesService{

    public function finaliarTramite($tramite){

        $url = 'http://127.0.0.1:8001/api/finalizar_tramite';

        try {

            $response = Http::acceptJson()->asForm()->post($url, [
                'tramite' => $tramite
            ]);

            $data = json_decode($response, true);

            if($data['result'] == 'error')
                Log::error("Error al enviar tramite concluido al sistema trámites. " . $response);

        } catch (\Throwable $th) {
            Log::error("Error al enviar tramite concluido al sistema trámites. " . $th->getMessage());
        }

    }

    public function rechazarTramite($tramite){

        $url = 'http://127.0.0.1:8001/api/rechazar_tramite';

        try {

            $response = Http::acceptJson()->asForm()->post($url, [
                'tramite' => $tramite
            ]);

            $data = json_decode($response, true);

            if($data['result'] == 'error')
                Log::error("Error al enviar tramite rechazado al sistema trámites. " . $response);

        } catch (\Throwable $th) {
            Log::error("Error al enviar tramite rechazado al sistema trámites. " . $th->getMessage());
        }

    }

}
