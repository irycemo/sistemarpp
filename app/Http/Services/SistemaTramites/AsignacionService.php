<?php

namespace App\Http\Services\SistemaTramites;

use App\Models\User;
use App\Exceptions\SupervisorNoEncontradoException;
use App\Exceptions\CertificadorNoEncontradoException;
use App\Models\MovimientoRegistral;

class AsignacionService{

    public function obtenerUltimoUsuarioConAsignacion($usuarios):int
    {

        $movimientos = [];

        foreach ($usuarios as $usuario) {

            if(!$usuario->ultimoMovimientoRegistralAsignado)
                return $usuario->id;

            array_push($movimientos, $usuario->ultimoMovimientoRegistralAsignado);

        }

        return collect($movimientos)->sortBy('created_at')->first()->usuario_asignado;

        /* return MovimientoRegistral::whereIn('id', $ids)->orderBy('created_at')->first()->usuario_asignado; */

    }

    public function obtenerUsuarioConsulta($distrito):int
    {

        $usuarios = User::with('ultimoMovimientoRegistralAsignado')
                                ->where('status', 'activo')
                                ->when($distrito == 2, function($q){
                                    $q->where('ubicacion', 'Regional 4');
                                })
                                ->when($distrito != 2, function($q){
                                    $q->where('ubicacion', '!=', 'Regional 4');
                                })
                                ->whereHas('roles', function($q){
                                    $q->where('name', 'Consulta');
                                })
                                ->get();

        if($usuarios->count() == 0){

            throw new CertificadorNoEncontradoException('No se encontraron usuario para asignar al movimiento registral.');

        }else if($usuarios->count() == 1){

            return $usuarios->first()->id;

        }else{

            return $this->obtenerUltimoUsuarioConAsignacion($usuarios);

        }

    }

    public function obtenerCertificador($distrito, $solicitante, $tipo_servicio, $random):int
    {

        if($distrito != 2 && $solicitante == 'Oficialia de partes'){

            if($tipo_servicio == 'ordinario')

                $certificadores = User::with('ultimoMovimientoRegistralAsignado')
                                        ->where('status', 'activo')
                                        ->whereHas('roles', function($q){
                                            $q->where('name', 'Certificador Oficialia');
                                        })
                                        ->get();
            else

                $certificadores = User::with('ultimoMovimientoRegistralAsignado')
                                        ->where('status', 'activo')
                                        ->whereHas('roles', function($q){
                                            $q->where('name', 'Certificador Juridico');
                                        })
                                        ->get();

        }else{

            $certificadores = User::with('ultimoMovimientoRegistralAsignado')
                                        ->where('status', 'activo')
                                        ->when($distrito == 2, function($q){
                                            $q->where('ubicacion', 'Regional 4');
                                        })
                                        ->when($distrito != 2, function($q){
                                            $q->where('ubicacion', '!=', 'Regional 4');
                                        })
                                        ->whereHas('roles', function($q){
                                            $q->where('name', 'Certificador');
                                        })
                                        ->get();

        }

        if($certificadores->count() == 0){
            throw new CertificadorNoEncontradoException('No se encontraron certificadores para asignar al movimiento registral.');

        }else if($random){

            $certificador = $certificadores->shuffle()->first();

            return $certificador->id;

        }else if($certificadores->count() == 1){

            return $certificadores->first()->id;

        }else{

            return $this->obtenerUltimoUsuarioConAsignacion($certificadores);

        }

    }

    public function obtenerSupervisorCertificaciones($distrito):int
    {

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
