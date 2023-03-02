<?php

namespace App\Http\Livewire\Admin;

use App\Models\Rancho;
use Livewire\Component;
use App\Models\Distrito;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ComponentesTrait;

class Ranchos extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public $distritos;

    public Rancho $modelo_editar;

    protected function rules(){
        return [
            'modelo_editar.nombre' => 'required',
            'modelo_editar.distrito_id' => 'required'
         ];
    }

    protected $validationAttributes  = [
        'distrito_id' => 'distrito',
    ];

    public function crearModeloVacio(){
        return Rancho::make();
    }

    public function abrirModalEditar(Rancho $modelo){

        $this->resetearTodo();
        $this->modal = true;
        $this->editar = true;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;
    }

    public function crear(){

        $this->validate();

        try {

            $this->modelo_editar->creado_por = auth()->user()->id;
            $this->modelo_editar->save();

            $this->resetearTodo();

            $this->dispatchBrowserEvent('mostrarMensaje', ['success', "El rancho se creó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al crear rancho por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function actualizar(){

        $this->validate();

        try{

            $this->modelo_editar->actualizado_por = auth()->user()->id;
            $this->modelo_editar->save();

            $this->resetearTodo();

            $this->dispatchBrowserEvent('mostrarMensaje', ['success', "El rancho se actualizó con éxito."]);


        } catch (\Throwable $th) {

            Log::error("Error al actualzar rancho por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function borrar(){

        try{

            $rancho = Rancho::find($this->selected_id);

            $rancho->delete();

            $this->resetearTodo($borrado = true);

            $this->dispatchBrowserEvent('mostrarMensaje', ['success', "El rancho se eliminó con exito."]);

        } catch (\Throwable $th) {

            Log::error("Error al borrar rancho por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function mount(){

        $this->modelo_editar = $this->crearModeloVacio();

        $this->distritos = Distrito::orderBy('nombre')->get();

    }

    public function render()
    {

        $ranchos = Rancho::with('creadoPor', 'actualizadoPor', 'distrito')
                                ->where('nombre', 'LIKE', '%' . $this->search . '%')
                                ->orderBy($this->sort, $this->direction)
                                ->orWhere(function($q){
                                    return $q->whereHas('distrito', function($q){
                                        return $q->where('nombre', 'LIKE', '%' . $this->search . '%');
                                    });
                                })
                                ->paginate($this->pagination);

        return view('livewire.admin.ranchos', compact('ranchos'))->extends('layouts.admin');

    }
}
