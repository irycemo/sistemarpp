<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Distrito;
use App\Models\Municipio;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ComponentesTrait;

class Municipios extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public $distritos;

    public Municipio $modelo_editar;

    protected function rules(){
        return [
            'modelo_editar.nombre' => 'required',
            'modelo_editar.distrito_id' => 'required'
         ];
    }

    public function crearModeloVacio(){
        return Municipio::make();
    }

    protected $validationAttributes  = [
        'distrito_id' => 'distrito',
    ];

    public function abrirModalEditar(Municipio $modelo){

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

            $this->dispatchBrowserEvent('mostrarMensaje', ['success', "El municipio se creó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al crear municipio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
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

            $this->dispatchBrowserEvent('mostrarMensaje', ['success', "El municipio se actualizó con éxito."]);


        } catch (\Throwable $th) {

            Log::error("Error al actualzar municipio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function borrar(){

        try{

            $municipio = Municipio::find($this->selected_id);

            $municipio->delete();

            $this->resetearTodo($borrado = true);

            $this->dispatchBrowserEvent('mostrarMensaje', ['success', "El municipio se eliminó con exito."]);

        } catch (\Throwable $th) {

            Log::error("Error al borrar municipio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
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

        $municipios = Municipio::with('creadoPor', 'actualizadoPor', 'distrito')
                                ->where('nombre', 'LIKE', '%' . $this->search . '%')
                                ->orWhere(function($q){
                                    return $q->whereHas('distrito', function($q){
                                        return $q->where('nombre', 'LIKE', '%' . $this->search . '%');
                                    });
                                })
                                ->orderBy($this->sort, $this->direction)
                                ->paginate($this->pagination);

        return view('livewire.admin.municipios', compact('municipios'))->extends('layouts.admin');
    }
}
