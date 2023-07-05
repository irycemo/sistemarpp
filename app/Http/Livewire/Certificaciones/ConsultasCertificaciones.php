<?php

namespace App\Http\Livewire\Certificaciones;

use App\Models\MovimientoRegistral;
use Livewire\Component;

class ConsultasCertificaciones extends Component
{

    public $certificacion;
    public $search;

    public function consultar(){

        $this->certificacion = MovimientoRegistral::where('tramite', $this->search)->first();

    }

    public function render()
    {
        return view('livewire.certificaciones.consultas-certificaciones')->extends('layouts.admin');
    }
}
