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

            Municipio::create([
                'nombre' => $this->nombre,
                'distrito_id' => $this->distrito_id,
                'creado_por' => auth()->user()->id
            ]);

            $this->resetearTodo();

            $this->dispatchBrowserEvent('mostrarMensaje', ['success', "El municipio se creó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al crear municipio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function actualizar(){

        try{

            $municipio = Municipio::find($this->selected_id);

            $municipio->update([
                'nombre' => $this->nombre,
                'distrito_id' => $this->distrito_id,
                'actualizado_por' => auth()->user()->id
            ]);

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

            $this->resetearTodo();

            $this->dispatchBrowserEvent('mostrarMensaje', ['success', "El municipio se eliminó con exito."]);

        } catch (\Throwable $th) {

            Log::error("Error al borrar municipio por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function mount(){

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
