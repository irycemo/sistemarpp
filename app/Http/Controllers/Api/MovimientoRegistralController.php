<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Certificacion;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use App\Http\Controllers\Controller;
use App\Http\Requests\MovimientoRegistralRequest;

class MovimientoRegistralController extends Controller
{
    public function store(MovimientoRegistralRequest $request)
    {

        try {

            $data = null;

            DB::transaction(function () use($request, &$data){


                $movimiento_registral = MovimientoRegistral::create($this->requestMovimiento($request));

                if($request->categoria_servicio == 'Certificaciones'){

                    Certificacion::create($this->requestTramtie($request) + ['movimiento_registral_id' => $movimiento_registral->id]);

                    $movimiento_registral->load('certificaciones');

                }

                $data = $movimiento_registral;

            });

            return $data;

        } catch (\Throwable $th) {

            return $th;

        }

    }

    public function obtenerCertificador(){

        $id = User::inRandomOrder()->whereHas('roles', function($q){
                                    $q->where('name', 'Supervisor Copias');
                                })->first()->id;

        return $id;

    }

    public function obtenerSupervisor(){

        $id = User::inRandomOrder()->whereHas('roles', function($q){
                                    $q->where('name', 'Certificador');
                                })->first()->id;

        return $id;

    }

    public function requestMovimiento($request){

        return [
            'monto' => $request->monto,
            'solicitante' => $request->solicitante,
            'tramite' => $request->tramite,
            'fecha_prelacion' => $request->fecha_prelacion,
            'tipo_servicio' => $request->tipo_servicio,
            'seccion' => $request->seccion,
            'distrito' => $request->distrito,
            'fecha_entrega' => $request->fecha_entrega,
            'usuario_asignado' => $this->obtenerCertificador(),
            'usuario_supervisor' => $this->obtenerSupervisor(),
            'estado' => 'nuevo',
            'tomo' => $request->tomo,
            'tomo_bis' => $request->tomo_bis,
            'registro' => $request->registro,
            'registro_bis' => $request->registro_bis,
        ];

    }

    public function requestTramtie($request){

        return $request->except(
            'monto',
            'solicitante',
            'tramite',
            'fecha_prelacion',
            'tipo_servicio',
            'seccion',
            'distrito',
            'fecha_entrega',
            'categoria_servicio',
            'tomo',
            'tomo_bis',
            'registro',
            'registro_bis'
        );

    }

}
