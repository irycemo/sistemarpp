<?php

namespace App\Http\Services\SistemaTramites;

use App\Exceptions\ErrorAlEnviarTramiteConcluidoASistemaTramites;
use App\Exceptions\ErrorAlEnviarTramiteRechazadoASistemaTramites;
use Illuminate\Support\Facades\Http;

class SistemaTramitesService{

    public function finaliarTramite($tramite){

        $url = env('SISTEMA_TRAMITES_FINALIZAR');

        $response = Http::acceptJson()->asForm()->post($url, [
            'tramite' => $tramite
        ]);

        if($response->status() != 200){

            throw new ErrorAlEnviarTramiteConcluidoASistemaTramites('Error al enviar tramite concluido al sistema trámites.' . $response);

        }

    }

    public function rechazarTramite($tramite, $observaciones){

        $url = env('SISTEMA_TRAMITES_RECHAZAR');

        $response = Http::acceptJson()->asForm()->post($url, [
            'tramite' => $tramite,
            'observaciones' => $observaciones,
        ]);

        if($response->status() != 200){

            throw new ErrorAlEnviarTramiteRechazadoASistemaTramites('Error al enviar tramite rechazado al sistema trámites.' . $response);

        }

    }

}
