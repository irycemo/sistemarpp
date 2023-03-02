<?php

namespace App\Http\Traits;

trait ComponentesTrait{

    public $modal = false;
    public $modalBorrar = false;
    public $crear = false;
    public $editar = false;
    public $search;
    public $sort = 'id';
    public $direction = 'desc';
    public $pagination = 10;
    public $selected_id;
    public $fields = ['modalBorrar', 'modal', 'crear', 'editar'];

    public function order($sort):void
    {

        if($this->sort == $sort){
            if($this->direction == 'desc'){
                $this->direction = 'asc';
            }else{
                $this->direction = 'desc';
            }
        }else{
            $this->sort = $sort;
            $this->direction = 'asc';
        }
    }

    public function updatedPagination():void
    {
        $this->resetPage();
    }

    public function updatingSearch():void
    {
        $this->resetPage();
    }

    public function resetearTodo($borrado = false):void
    {

        $this->reset($this->fields);
        $this->resetErrorBag();
        $this->resetValidation();

        if($borrado)
            $this->modelo_editar = $this->crearModeloVacio();

    }

    public function abrirModalBorrar($id):void
    {

        $this->modalBorrar = true;

        $this->selected_id = $id;

    }

    public function abrirModalCrear():void
    {

        $this->resetearTodo();
        $this->modal = true;
        $this->crear =true;

        if($this->modelo_editar->getKey())
            $this->modelo_editar = $this->crearModeloVacio();

    }

    public function mount():void
    {

        $this->modelo_editar = $this->crearModeloVacio();

    }

}
