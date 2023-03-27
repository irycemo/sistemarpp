<?php

namespace App\Http\Livewire\Certificaciones;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Certificacion;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ComponentesTrait;

class CopiasSimples extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public Certificacion $modelo_editar;

    public function crearModeloVacio(){
        return Certificacion::make();
    }

    public function abrirModalEditar(Certificacion $modelo){

        $this->resetearTodo();
        $this->modal = true;
        $this->editar = true;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

    }

    public function finalizarSupervisor(Certificacion $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        if($this->modelo_editar->folio_carpeta_copias == null){

            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "EL campo Folio de carpeta es obligatorio."]);
            return;

        }

        try {


            $this->modelo_editar->finalizado_en = now();

            $this->modelo_editar->firma = now();

            $this->modelo_editar->actualizado_por = auth()->user()->id;

            $this->modelo_editar->save();

            $this->resetearTodo();

            $this->dispatchBrowserEvent('mostrarMensaje', ['success', "El trámite se finalizó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de copias simples por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function finalizar(){

        $this->validate();

        try{

            $this->modelo_editar->actualizado_por = auth()->user()->id;

            $this->modelo_editar->save();

            $this->resetearTodo();

            $this->dispatchBrowserEvent('mostrarMensaje', ['success', "El trámite se finalizó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de copias simples por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();
        }

    }

    public function render()
    {

        $copias = Certificacion::with('movimientoRegistral', 'actualizadoPor')
                                    ->where('servicio', 'Copias Simples')
                                    ->whereNull('finalizado_en')
                                    ->where(function($q){

                                        return $q->where('numero_paginas', 'LIKE', '%' . $this->search . '%')
                                                    ->orWhere(function($q){
                                                        return $q->whereHas('movimientoRegistral', function($q){
                                                            return $q->where('solicitante', 'LIKE', '%' . $this->search . '%')
                                                                        ->orWhere('tomo', 'LIKE', '%' . $this->search . '%')
                                                                        ->orWhere('registro', 'LIKE', '%' . $this->search . '%');
                                                        });
                                                    });
                                    })
                                    ->orderBy($this->sort, $this->direction)
                                    ->paginate($this->pagination);


        return view('livewire.certificaciones.copias-simples', compact('copias'))->extends('layouts.admin');
    }
}
