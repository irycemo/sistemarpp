<?php

namespace App\Http\Livewire\Certificaciones;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Http\Services\SistemaTramites\SistemaTramitesService;

class ConsultasCertificaciones extends Component
{

    public $certificacion;
    public $search;
    public $modal;
    public $modal2;
    public $modalRechazar;
    public $paginas;
    public $observaciones;
    public $usuarios;
    public $usuario;

    public function save(){

        $this->validate(['paginas' => 'required']);

        if($this->certificacion->estado != 'nuevo'){

            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "El trámite esta concluido."]);

            $this->modal = false;

            $this->reset('paginas');

            return;

        }

        if($this->paginas >= $this->certificacion->certificacion->numero_paginas){

            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "El número de paginas no puede ser mayor o igual al registrado."]);

            $this->modal = false;

            $this->reset('paginas');

            return;

        }

        $this->certificacion->certificacion->update(['numero_paginas' => $this->paginas]);

        $this->dispatchBrowserEvent('mostrarMensaje', ['success', "Se actualizó la información con éxito."]);

        $this->modal = false;

        $this->reset('paginas');

    }

    public function rechazar(){

        $this->validate([
            'observaciones' => 'required'
        ]);

        try {

            DB::transaction(function (){

                $observaciones = auth()->user()->name . ' rechaza el ' . now() . ', con motivo: ' . $this->observaciones ;

                (new SistemaTramitesService())->rechazarTramite($this->certificacion->tramite, $observaciones);

                $this->certificacion->update(['estado' => 'rechazado']);

                $this->certificacion->actualizado_por = auth()->user()->id;

                $this->certificacion->certificacion->observaciones = $this->certificacion->certificacion->observaciones . $observaciones;

                $this->certificacion->certificacion->save();

                $this->certificacion->save();

                $this->dispatchBrowserEvent('mostrarMensaje', ['success', "El trámite se rechazó con éxito."]);

                $this->modalRechazar = false;

            });

        } catch (\Throwable $th) {
            Log::error("Error al rechazar trámite de copias certificadas por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function reasignar(){

        $this->validate([
            'usuario' => 'required'
        ]);

        try {

            DB::transaction(function (){

                $this->certificacion->update(['usuario_asignado' => $this->usuario]);

                $this->dispatchBrowserEvent('mostrarMensaje', ['success', "El trámite se reasigno con éxito."]);

                $this->modal2 = false;

            });

        } catch (\Throwable $th) {
            Log::error("Error al reasignar trámite por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function consultar(){

        $this->certificacion = MovimientoRegistral::where('tramite', $this->search)->first();

    }

    public function mount(){

        $this->usuarios = User::whereHas('roles', function($q){
                                        $q->where('name', 'Certificador');
                                    })
                                    ->orderBy('name')
                                    ->get();

    }

    public function render()
    {
        return view('livewire.certificaciones.consultas-certificaciones')->extends('layouts.admin');
    }
}
