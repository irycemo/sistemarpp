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
    public $pagination=10;
    public $selected_id;

    public function order($sort){

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

    public function updatedPagination(){
        $this->resetPage();
    }

    public function updatingSearch(){
        $this->resetPage();
    }

    public function abrirModalBorrar($model){

        $this->modalBorrar = true;

        $this->selected_id = $model['id'];

    }

    public function abrirModalCrear(){
        $this->resetearTodo();
        $this->modal = true;
        $this->crear =true;
    }

}
