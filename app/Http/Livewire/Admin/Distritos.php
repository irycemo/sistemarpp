<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Distrito;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ComponentesTrait;

class Distritos extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public Distrito $modelo_editar;

    protected function rules(){
        return [
            'modelo_editar.nombre' => 'required',
            'modelo_editar.clave' => 'required'
         ];
    }

    public function crearModeloVacio(){
        return Distrito::make();
    }

    public function abrirModalEditar(Distrito $modelo){

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

            $this->dispatchBrowserEvent('mostrarMensaje', ['success', "El distrito se creó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al crear distrito por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
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

            $this->dispatchBrowserEvent('mostrarMensaje', ['success', "El distrito se actualizó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al actualzar distrito por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function borrar(){

        try{

            $distrito = Distrito::find($this->selected_id);

            $distrito->delete();

            $this->resetearTodo($borrado = true);

            $this->dispatchBrowserEvent('mostrarMensaje', ['success', "El distrito se eliminó con exito."]);

        } catch (\Throwable $th) {

            Log::error("Error al borrar distrito por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function render()
    {

        $distritos = Distrito::with('creadoPor', 'actualizadoPor')
                                ->where('nombre', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('clave', $this->search)
                                ->orderBy($this->sort, $this->direction)
                                ->paginate($this->pagination);

        return view('livewire.admin.distritos', compact('distritos'))->extends('layouts.admin');

    }
}
