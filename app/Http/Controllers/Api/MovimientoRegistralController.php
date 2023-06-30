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

    public function obtenerUsuarioAsignado($servicio, $distrito, $solicitante, $tipo_servicio){

        /* Certificaciones: Copias simples, Copias certificadas */
        if($servicio == 'DL13' || $servicio == 'DL14'){

            return $this->obtenerCertificador($distrito, $solicitante, $tipo_servicio);

        }

        /* Certificaciones: Consultas */
        if($servicio == 'DC90' || $servicio == 'DC91' || $servicio == 'DC92' || $servicio == 'DC93'){

            return $this->obtenerUsuarioConsulta($distrito);

        }

    }

    public function obtenerSupervisor($distrito){

        return $this->obtenerSupervisorCertificaciones($distrito);

    }

    public function requestMovimiento($request){

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
            'usuario_asignado' => $this->obtenerUsuarioAsignado($request->servicio, $request->distrito, $request->solicitante, $request->tipo_servicio),
            'usuario_supervisor' => $this->obtenerSupervisor($request->distrito),
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
            'numero_oficio'
        );

    }

    public function obtenerCertificador($distrito, $solicitante, $tipo_servicio){

        if($distrito != 2 && $solicitante == 'Oficialia de partes'){

            if($tipo_servicio != 'extra_urgente')

                $certificadores = User::where('status', 'activo')
                                        ->whereHas('roles', function($q){
                                            $q->where('name', 'Certificador Juridico');
                                            })
                                        ->withCount(['movimientosRegistralesAsignados' => function($q){
                                            $q->where('estado', 'nuevo');
                                        }])
                                        ->get();
            else

                $certificadores = User::where('status', 'activo')
                                        ->when($distrito == 2, function($q){
                                            $q->where('ubicacion', 'Regional 4');
                                        })
                                        ->when($distrito != 2, function($q){
                                            $q->where('ubicacion', '!=', 'Regional 4');
                                        })
                                        ->whereHas('roles', function($q){
                                            $q->where('name', 'Certificador Oficialia');
                                            })
                                        ->withCount(['movimientosRegistralesAsignados' => function($q){
                                            $q->where('estado', 'nuevo');
                                        }])
                                        ->get();

        }else{

            $certificadores = User::where('status', 'activo')
                                        ->when($distrito == 2, function($q){
                                            $q->where('ubicacion', 'Regional 4');
                                        })
                                        ->when($distrito != 2, function($q){
                                            $q->where('ubicacion', '!=', 'Regional 4');
                                        })
                                        ->whereHas('roles', function($q){
                                            $q->where('name', 'Certificador');
                                            })
                                        ->withCount(['movimientosRegistralesAsignados' => function($q){
                                            $q->where('estado', 'nuevo');
                                        }])
                                        ->get();

        }

        if($certificadores->count() == 0){

            throw new CertificadorNoEncontradoException('No se encontraron certificadores para asignar al movimiento registral.');
        }

        $certificador = $certificadores->sortBy('movimientos_registrales_asignados_count')->first();

        return $certificador->id;

    }

    public function obtenerUsuarioConsulta($distrito){

        $usuario = User::inRandomOrder()->where('status', 'activo')
                                            ->when($distrito == 2, function($q){
                                                $q->where('ubicacion', 'Regional 4');
                                            })
                                            ->when($distrito != 2, function($q){
                                                $q->where('ubicacion', '!=', 'Regional 4');
                                            })
                                            ->whereHas('roles', function($q){
                                                $q->where('name', 'Consulta');
                                            })
                                            ->first();

        if(!$usuario){

            throw new CertificadorNoEncontradoException('No se encontraron usuarios de consulta para asignar al movimiento registral.');
        }

        return $usuario->id;

    }

    public function obtenerSupervisorCertificaciones($distrito){

        $supervisor = User::inRandomOrder()
                                ->where('status', 'activo')
                                ->when($distrito == 2, function($q){
                                    $q->where('ubicacion', 'Regional 4');
                                })
                                ->when($distrito != 2, function($q){
                                    $q->where('ubicacion', '!=', 'Regional 4');
                                })
                                ->whereHas('roles', function($q){
                                    $q->where('name', 'Supervisor Copias');
                                })
                                ->first();

        if(!$supervisor){

            throw new SupervisorNoEncontradoException('No se encontraron supervisores para asignar al movimiento registral.');

        }

        return $supervisor->id;

    }

}
