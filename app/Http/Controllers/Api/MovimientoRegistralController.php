<?php

namespace App\Http\Controllers\Api;

use App\Models\Certificacion;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\MovimientoRegistralRequest;
use App\Http\Services\SistemaTramites\AsignacionService;

class MovimientoRegistralController extends Controller
{

    public function __construct(public AsignacionService $asignacionService){}

    public function store(MovimientoRegistralRequest $request)
    {

        try {

            $data = null;

            DB::transaction(function () use($request, &$data){


                $movimiento_registral = MovimientoRegistral::create($this->requestMovimientoCrear($request));

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

            Log::error("Error al crear movimiento registral desde Sistema Trámites. " . $th->getMessage());

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

                $movimiento_registral->update($this->requestMovimientoActualizar($request));

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

            Log::error("Error al actualizar movimiento registral desde Sistema Trámites. " . $th->getMessage());

            return response()->json([
                'result' => 'error',
                'data' => $th->getMessage(),
            ], 500);

        }

    }

    public function obtenerUsuarioAsignado($servicio, $distrito, $solicitante, $tipo_servicio, $random){

        /* Certificaciones: Copias simples, Copias certificadas */
        if($servicio == 'DL13' || $servicio == 'DL14'){

            return $this->asignacionService->obtenerCertificador($distrito, $solicitante, $tipo_servicio, $random);

        }

        /* Certificaciones: Consultas */
        if($servicio == 'DC90' || $servicio == 'DC91' || $servicio == 'DC92' || $servicio == 'DC93'){

            return $this->asignacionService->obtenerUsuarioConsulta($distrito);

        }

    }

    public function obtenerSupervisor($distrito){

        return $this->asignacionService->obtenerSupervisorCertificaciones($distrito);

    }

    public function requestMovimientoCrear($request){

        return [
            'folio_real' => $request->folio_real,
            'monto' => $request->monto,
            'solicitante' => $request->nombre_solicitante,
            'tramite' => $request->tramite,
            'fecha_prelacion' => $request->fecha_prelacion,
            'fecha_pago' => $request->fecha_pago,
            'tipo_servicio' => $request->tipo_servicio,
            'seccion' => $request->seccion,
            'distrito' => $request->distrito,
            'fecha_entrega' => $request->fecha_entrega,
            'usuario_asignado' => $this->obtenerUsuarioAsignado($request->servicio, $request->distrito, $request->solicitante, $request->tipo_servicio, false),
            'usuario_supervisor' => $this->obtenerSupervisor($request->distrito),
            'estado' => 'nuevo',
            'tomo' => $request->tomo,
            'tomo_bis' => $request->tomo_bis,
            'registro' => $request->registro,
            'registro_bis' => $request->registro_bis,
            'numero_oficio' => $request->numero_oficio,
        ];

    }

    public function requestMovimientoActualizar($request){

        return [
            'solicitante' => $request->nombre_solicitante,
            'seccion' => $request->seccion,
            'distrito' => $request->distrito,
            'estado' => 'nuevo',
            'tomo' => $request->tomo,
            'tomo_bis' => $request->tomo_bis,
            'registro' => $request->registro,
            'registro_bis' => $request->registro_bis,
            'numero_oficio' => $request->numero_oficio,
        ];

    }

    public function requestTramtie($request){

        return $request->except(
            'folio_real',
            'monto',
            'solicitante',
            'nombre_solicitante',
            'tramite',
            'fecha_prelacion',
            'tipo_servicio',
            'seccion',
            'distrito',
            'fecha_entrega',
            'fecha_pago',
            'categoria_servicio',
            'numero_notaria',
            'tomo',
            'tomo_bis',
            'registro',
            'registro_bis',
            'numero_oficio',
            'movimiento_registral'
        );

    }

}
