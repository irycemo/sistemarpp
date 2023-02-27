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

            Rancho::create([
                'nombre' => $this->nombre,
                'distrito_id' => $this->distrito_id,
                'creado_por' => auth()->user()->id
            ]);

            $this->resetearTodo();

            $this->dispatchBrowserEvent('mostrarMensaje', ['success', "El rancho se creó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al crear rancho por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function actualizar(){

        try{

            $rancho = Rancho::find($this->selected_id);

            $rancho->update([
                'nombre' => $this->nombre,
                'distrito_id' => $this->distrito_id,
                'actualizado_por' => auth()->user()->id
            ]);

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

            $this->resetearTodo();

            $this->dispatchBrowserEvent('mostrarMensaje', ['success', "El rancho se eliminó con exito."]);

        } catch (\Throwable $th) {

            Log::error("Error al borrar rancho por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function mount(){

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
