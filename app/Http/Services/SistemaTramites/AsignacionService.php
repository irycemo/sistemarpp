<?php

namespace App\Http\Services\SistemaTramites;

use App\Models\User;
use App\Exceptions\SupervisorNoEncontradoException;
use App\Exceptions\CertificadorNoEncontradoException;

class AsignacionService{

    public function obtenerUltimoUsuarioConAsignacion($usuarios):int
    {

        if($usuarios->first()->movimientosRegistralesAsignados->count() == 0)
            return $usuarios->first()->id;

        $ultimoMR = $usuarios->first()->movimientosRegistralesAsignados->last();

        foreach ($usuarios as $usuario) {

            $usuario->load('movimientosRegistralesAsignados');

            if($usuario->movimientosRegistralesAsignados->count() == 0)
                return $usuario->id;

            if($ultimoMR->created_at > $usuario->movimientosRegistralesAsignados->last()->created_at)
                $ultimoMR = $usuario->movimientosRegistralesAsignados->last();

        }

        return $ultimoMR->usuario_asignado;

    }

    public function obtenerUsuarioConsulta($distrito):int
    {

        $usuarios = User::where('status', 'activo')
                                ->when($distrito == 2, function($q){
                                    $q->where('ubicacion', 'Regional 4');
                                })
                                ->when($distrito != 2, function($q){
                                    $q->where('ubicacion', '!=', 'Regional 4');
                                })
                                ->whereHas('roles', function($q){
                                    $q->where('name', 'Consulta');
                                })
                                ->with('movimientosRegistralesAsignados', function($q){
                                    $q->where('estado', 'nuevo');
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

            if($tipo_servicio != 'extra_urgente')

                $certificadores = User::where('status', 'activo')
                                        ->whereHas('roles', function($q){
                                            $q->where('name', 'Certificador Juridico');
                                        })
                                        ->get();
            else

                $certificadores = User::where('status', 'activo')
                                        ->whereHas('roles', function($q){
                                            $q->where('name', 'Certificador Oficialia');
                                        })
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
