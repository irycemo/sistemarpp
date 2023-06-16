<?php

namespace App\Http\Livewire\Certificaciones;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Certificacion;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ComponentesTrait;

class Consultas extends Component
{

    use ComponentesTrait;
    use WithPagination;

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

    public function finalizar(){

        try{

            $this->modelo_editar->movimientoRegistral->update(['estado' => 'concluido']);
            $this->modelo_editar->actualizado_por = auth()->user()->id;
            $this->modelo_editar->finalizado_en = now();

            $this->modelo_editar->save();

            $this->resetearTodo();

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de consulta por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();
        }

    }

    public function render()
    {

        if(auth()->user()->hasRole('Administrador')){

            $consultas = Certificacion::with('movimientoRegistral', 'actualizadoPor')
                                        ->whereIn('servicio', ['DC90', 'DC91', 'DC92', 'DC93'])
                                        ->whereHas('movimientoRegistral', function($q){
                                            $q->when(auth()->user()->ubicacion == 'Regional 2', function($q){
                                                $q->where('distrito', 2);
                                            });
                                        })
                                        ->where(function($q){

                                            return $q->where(function($q){
                                                            return $q->whereHas('movimientoRegistral', function($q){
                                                                return $q->where('solicitante', 'LIKE', '%' . $this->search . '%')
                                                                            ->orWhere('tomo', 'LIKE', '%' . $this->search . '%')
                                                                            ->orWhere('registro', 'LIKE', '%' . $this->search . '%');
                                                            });
                                                        });

                                        })
                                        ->orderBy($this->sort, $this->direction)
                                        ->paginate($this->pagination);


        }else{

            $consultas = Certificacion::with('movimientoRegistral', 'actualizadoPor')
                                        ->whereHas('movimientoRegistral', function($q){
                                            $q->where('estado', 'nuevo');
                                        })
                                        ->whereIn('servicio', ['DC90', 'DC91', 'DC92', 'DC93'])
                                        ->whereNull('finalizado_en')
                                        ->where(function($q){

                                            return $q->where(function($q){
                                                            return $q->whereHas('movimientoRegistral', function($q){
                                                                return $q->where('solicitante', 'LIKE', '%' . $this->search . '%')
                                                                            ->orWhere('tomo', 'LIKE', '%' . $this->search . '%')
                                                                            ->orWhere('registro', 'LIKE', '%' . $this->search . '%');
                                                            });
                                                        });

                                        })
                                        ->orderBy($this->sort, $this->direction)
                                        ->paginate($this->pagination);

        }

        return view('livewire.certificaciones.consultas', compact('consultas'))->extends('layouts.admin');
    }
}
