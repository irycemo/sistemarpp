<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Http\Constantes;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ComponentesTrait;
use Spatie\Permission\Models\Permission;

class Permisos extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public $areas = [];

    public $nombre;
    public $area;

    protected function rules(){
        return [
            'nombre' => 'required',
            'area' => 'required'
         ];
    }

    protected $validationAttributes  = [
        'area' => 'área',
    ];

    public function resetearTodo(){

        $this->reset(['modalBorrar', 'crear', 'editar', 'modal', 'nombre', 'area']);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function abrirModalEditar($modelo){

        $this->resetearTodo();
        $this->modal = true;
        $this->editar = true;

        $this->selected_id = $modelo['id'];
        $this->nombre = $modelo['name'];
        $this->area = $modelo['area'];

    }

    public function crear(){

        $this->validate();

        try {

            Permission::create([
                'name' => $this->nombre,
                'area' => $this->area,
                'creado_por' => auth()->user()->id
            ]);

            $this->resetearTodo();

            $this->dispatchBrowserEvent('mostrarMensaje', ['success', "El permiso se creó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al crear permiso por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function actualizar(){

        $this->validate();

        try{

            $permiso = Permission::find($this->selected_id);

            $permiso->update([
                'name' => $this->nombre,
                'area' => $this->area,
                'actualizado_por' => auth()->user()->id
            ]);

            $this->resetearTodo();

            $this->dispatchBrowserEvent('mostrarMensaje', ['success', "El permiso se actualizó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al actualizar permiso por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function borrar(){

        try{

            $permiso = Permission::find($this->selected_id);

            $permiso->delete();

            $this->resetearTodo();

            $this->dispatchBrowserEvent('mostrarMensaje', ['success', "El permiso se eliminó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al borrar permiso por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function mount(){

        $this->areas = Constantes::AREAS;

        sort($this->areas);

    }

    public function render()
    {

        $permisos = Permission::with('creadoPor', 'actualizadoPor')
                                ->where('name', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('area', 'LIKE', '%' . $this->search . '%')
                                ->orderBy($this->sort, $this->direction)
                                ->paginate($this->pagination);

        return view('livewire.admin.permisos', compact('permisos'))->extends('layouts.admin');
    }

}
