<?php

namespace App\Http\Livewire\Certificaciones;

use Livewire\Component;
use App\Models\MovimientoRegistral;

class ConsultasCertificaciones extends Component
{

    public $certificacion;
    public $search;
    public $modal;
    public $paginas;

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

    public function consultar(){

        $this->certificacion = MovimientoRegistral::where('tramite', $this->search)->first();

    }

    public function render()
    {
        return view('livewire.certificaciones.consultas-certificaciones')->extends('layouts.admin');
    }
}
