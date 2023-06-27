<?php

namespace App\Http\Livewire\Admin;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use App\Http\Constantes;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ComponentesTrait;

class Usuarios extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public $roles;
    public $ubicaciones;
    public $areas_adscripcion;

    public User $modelo_editar;
    public $role;

    protected function rules(){
        return [
            'modelo_editar.name' => 'required',
            'modelo_editar.email' => 'required|email:rfc,dns|unique:users,email,' . $this->modelo_editar->id,
            'modelo_editar.status' => 'required|in:activo,inactivo',
            'role' => 'required',
            'modelo_editar.ubicacion' => 'required',
            'modelo_editar.area' => 'required'
         ];
    }

    protected $validationAttributes  = [
        'role' => 'rol',
        'modelo_editar.ubicacion' => 'ubicación'
    ];

    public function crearModeloVacio(){
        return User::make();
    }

    public function abrirModalEditar(User $modelo){

        $this->resetearTodo();
        $this->modal = true;
        $this->editar = true;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->role = $modelo['roles'][0]['id'];

    }

    public function crear(){

        $this->validate();

        if(User::where('name', $this->modelo_editar->name)->first()){

            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "El usuario " . $this->modelo_editar->name . " ya esta registrado."]);

            $this->resetearTodo();

            return;

        }

        if($this->role == 9){

            $user = User::whereHas('roles', function ($q){
                            return $q->where('name', 'Certificador Oficialia');
                        })
                        ->where('status', 'activo')
                        ->first();

            if($user){

                $this->dispatchBrowserEvent('mostrarMensaje', ['error', "El usuario solo puede haber 1 certificador de oficialia activo."]);

                $this->resetearTodo();

                return;

            }

        }

        try {

            DB::transaction(function () {

                $this->modelo_editar->password = 'sistema';
                $this->modelo_editar->creado_por = auth()->user()->id;
                $this->modelo_editar->save();

                $this->modelo_editar->roles()->attach($this->role);

                $this->resetearTodo();

                $this->dispatchBrowserEvent('mostrarMensaje', ['success', "El usuario se creó con éxito."]);

            });

        } catch (\Throwable $th) {

            Log::error("Error al crear usuario por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();
        }

    }

    public function actualizar(){

        $this->validate();

        $user = User::whereHas('roles', function ($q){
                    return $q->where('name', 'Certificador Oficialia');
                })
                ->where('status', 'activo')
                ->first();

        if($this->role == 9){

            $user = User::whereHas('roles', function ($q){
                            return $q->where('name', 'Certificador Oficialia');
                        })
                        ->where('status', 'activo')
                        ->first();

            if($user){

                $this->dispatchBrowserEvent('mostrarMensaje', ['error', "El usuario solo puede haber 1 certificador de oficialia activo."]);

                $this->resetearTodo();

                return;

            }

        }

        try{

            DB::transaction(function () {

                $this->modelo_editar->actualizado_por = auth()->user()->id;
                $this->modelo_editar->save();

                $this->modelo_editar->roles()->sync($this->role);

                $this->resetearTodo();

                $this->dispatchBrowserEvent('mostrarMensaje', ['success', "El usuario se actualizó con éxito."]);

            });

        } catch (\Throwable $th) {

            Log::error("Error al actualizar usuario por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();
        }

    }

    public function borrar(){

        try{

            $usuario = User::find($this->selected_id);

            $usuario->delete();

            $this->resetearTodo($borrado = true);

            $this->dispatchBrowserEvent('mostrarMensaje', ['success', "El usuario se eliminó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al borrar usuario por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();
        }

    }

    public function mount(){

        $this->modelo_editar = $this->crearModeloVacio();

        array_push($this->fields, 'role');

        $this->roles = Role::where('id', '!=', 1)->select('id', 'name')->orderBy('name')->get();

        $this->ubicaciones = Constantes::UBICACIONES;

        sort($this->ubicaciones);

        $this->areas_adscripcion = Constantes::AREAS_ADSCRIPCION;

        sort($this->areas_adscripcion);

    }

    public function render()
    {

        $usuarios = User::with('creadoPor', 'actualizadoPor')
                            ->where('name', 'LIKE', '%' . $this->search . '%')
                            ->orWhere('email', 'LIKE', '%' . $this->search . '%')
                            ->orWhere('ubicacion', 'LIKE', '%' . $this->search . '%')
                            ->orWhere('area', 'LIKE', '%' . $this->search . '%')
                            ->orWhere('status', 'LIKE', '%' . $this->search . '%')
                            ->orWhere(function($q){
                                return $q->whereHas('roles', function($q){
                                    return $q->where('name', 'LIKE', '%' . $this->search . '%');
                                });
                            })
                            ->orderBy($this->sort, $this->direction)
                            ->paginate($this->pagination);

        return view('livewire.admin.usuarios', compact('usuarios'))->extends('layouts.admin');
    }

}
