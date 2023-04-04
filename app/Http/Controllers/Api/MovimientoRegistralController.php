<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\CertificadorNoEncontradoException;
use App\Exceptions\SupervisorNoEncontradoException;
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

                    $movimiento_registral->load('certificacion');

                }

                $data = $movimiento_registral;

            });

            return response()->json([
                'result' => 'success',
                'data' => $data
            ], 200);

        } catch (\Throwable $th) {

            return response()->json([
                'result' => 'error',
                'data' => $th->getMessage(),
            ], 500);

        }

    }

    public function update(MovimientoRegistralRequest $request){

        try {

            $data = null;

            DB::transaction(function () use($request, &$data){


                $movimiento_registral = MovimientoRegistral::findOrFail($request->movimiento_registral);

                $movimiento_registral->update(['estado' => 'nuevo']);

                if($request->categoria_servicio == 'Certificaciones'){

                    $movimiento_registral->certificacion->update([
                        'numero_paginas' => $movimiento_registral->certificacion->numero_paginas + $request->numero_paginas
                    ]);

                    $movimiento_registral->load('certificacion');

                }

                $data = $movimiento_registral;

            });

            return response()->json([
                'result' => 'success',
                'data' => $data
            ], 200);

        } catch (\Throwable $th) {

            return response()->json([
                'result' => 'error',
                'data' => $th->getMessage(),
            ], 500);

        }

    }

    public function obtenerCertificador(){

        $certificador = User::inRandomOrder()
                                ->whereHas('roles', function($q){
                                    $q->where('name', 'Certificador');
                                })
                                ->first();

        if(!$certificador){

            throw new CertificadorNoEncontradoException('No se encontraron certificadores para asignar al movimiento registral.');
        }


        return $certificador->id;

    }

    public function obtenerSupervisor(){

        $supervisor = User::inRandomOrder()->whereHas('roles', function($q){
                                    $q->where('name', 'Supervisor Copias');
                                })->first();

        if(!$supervisor){

            throw new SupervisorNoEncontradoException('No se encontraron supervisores para asignar al movimiento registral.');
        }

        return $supervisor->id;

    }

    public function requestMovimiento($request){

        return [
            'folio_real' => $request->folio_real,
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
            'folio_real',
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
