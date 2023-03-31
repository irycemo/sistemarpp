<?php

namespace App\Http\Services\SistemaTramites;

use App\Exceptions\ErrorAlEnviarTramiteConcluidoASistemaTramites;
use App\Exceptions\ErrorAlEnviarTramiteRechazadoASistemaTramites;
use Illuminate\Support\Facades\Http;

class SistemaTramitesService{

    public function finaliarTramite($tramite){

        $url = 'http://127.0.0.1:8001/api/finalizar_tramite';

        $response = Http::acceptJson()->asForm()->post($url, [
            'tramite' => $tramite
        ]);

        if($response->status() != 200){

            throw new ErrorAlEnviarTramiteConcluidoASistemaTramites('Error al enviar tramite concluido al sistema trámites.' . $response);

        }

    }

    public function rechazarTramite($tramite, $observaciones){

        $url = 'http://127.0.0.1:8001/api/rechazar_tramite';

        $response = Http::acceptJson()->asForm()->post($url, [
            'tramite' => $tramite,
            'observaciones' => $observaciones,
        ]);

        if($response->status() != 200){

            throw new ErrorAlEnviarTramiteRechazadoASistemaTramites('Error al enviar tramite rechazado al sistema trámites.' . $response);

        }

    }

}
