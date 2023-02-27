<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Distrito;
use App\Models\Tenencia;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ComponentesTrait;

class Tenencias extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public $distritos;

    public $nombre;
    public $distrito_id;

    protected function rules(){
        return [
            'nombre' => 'required',
            'distrito_id' => 'required'
         ];
    }

    protected $validationAttributes  = [
        'distrito_id' => 'distrito',
    ];

    public function resetearTodo(){

        $this->reset(['modalBorrar', 'crear', 'editar', 'modal', 'nombre', 'distrito_id']);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function abrirModalEditar($modelo){

        $this->resetearTodo();
        $this->modal = true;
        $this->editar = true;

        $this->selected_id = $modelo['id'];
        $this->nombre = $modelo['nombre'];
        $this->distrito_id = $modelo['distrito_id'];

    }

    public function crear(){

        $this->validate();

        try {

            Tenencia::create([
                'nombre' => $this->nombre,
                'distrito_id' => $this->distrito_id,
                'creado_por' => auth()->user()->id
            ]);

            $this->resetearTodo();

            $this->dispatchBrowserEvent('mostrarMensaje', ['success', "La tenencia se creó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al crear tenencia por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function actualizar(){

        try{

            $tenencia = Tenencia::find($this->selected_id);

            $tenencia->update([
                'nombre' => $this->nombre,
                'distrito_id' => $this->distrito_id,
                'actualizado_por' => auth()->user()->id
            ]);

            $this->resetearTodo();

            $this->dispatchBrowserEvent('mostrarMensaje', ['success', "La tenencia se actualizó con éxito."]);


        } catch (\Throwable $th) {

            Log::error("Error al actualzar tenencia por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function borrar(){

        try{

            $tenencia = Tenencia::find($this->selected_id);

            $tenencia->delete();

            $this->resetearTodo();

            $this->dispatchBrowserEvent('mostrarMensaje', ['success', "La tenencia se eliminó con exito."]);

        } catch (\Throwable $th) {

            Log::error("Error al borrar tenencia por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function mount(){

        $this->distritos = Distrito::orderBy('nombre')->get();

    }

    public function render()
    {

        $tenencias = Tenencia::with('creadoPor', 'actualizadoPor', 'distrito')
                                ->where('nombre', 'LIKE', '%' . $this->search . '%')
                                ->orderBy($this->sort, $this->direction)
                                ->orWhere(function($q){
                                    return $q->whereHas('distrito', function($q){
                                        return $q->where('nombre', 'LIKE', '%' . $this->search . '%');
                                    });
                                })
                                ->paginate($this->pagination);

        return view('livewire.admin.tenencias', compact('tenencias'))->extends('layouts.admin');
    }
}
