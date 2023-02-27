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

    public $nombre;
    public $clave;

    protected function rules(){
        return [
            'nombre' => 'required',
            'clave' => 'required'
         ];
    }

    public function resetearTodo(){

        $this->reset(['modalBorrar', 'crear', 'editar', 'modal', 'nombre', 'clave']);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function abrirModalEditar($modelo){

        $this->resetearTodo();
        $this->modal = true;
        $this->editar = true;

        $this->selected_id = $modelo['id'];
        $this->nombre = $modelo['nombre'];
        $this->clave = $modelo['clave'];

    }

    public function crear(){

        $this->validate();

        try {

            Distrito::create([
                'nombre' => $this->nombre,
                'clave' => $this->clave,
                'creado_por' => auth()->user()->id
            ]);

            $this->resetearTodo();

            $this->dispatchBrowserEvent('mostrarMensaje', ['success', "El distrito se creó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al crear distrito por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function actualizar(){

        try{

            $distrito = Distrito::find($this->selected_id);

            $distrito->update([
                'name' => $this->nombre,
                'clave' => $this->clave,
                'actualizado_por' => auth()->user()->id
            ]);

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

            $this->resetearTodo();

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
