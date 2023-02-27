<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use App\Models\Audit;
use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Traits\ComponentesTrait;

class Auditoria extends Component
{

    use ComponentesTrait;
    use WithPagination;

    public $usuario;
    public $evento;
    public $modelo;
    public $selecetedAudit;
    public $selecetedAuditSync;
    public $oldRole;
    public $newRole;
    public $modelos = [
        'App\Models\User',
    ];

    public function ver($audit){

        if($audit['event'] == 'sync'){


            $this->oldRole = json_decode($audit['old_values'])->roles[0]->name;

            $this->newRole =json_decode($audit['new_values'])->roles[0]->name;

        }

        $this->selecetedAudit = $audit;

        $this->modal = true;

    }

    public function render()
    {

        $usuarios = User::select('id', 'name')->orderBy('name')->get();

        $audits = Audit::with('user')
                            ->when(isset($this->usuario) && $this->usuario != "", function($q){
                                return $q->where('user_id', $this->usuario);

                            })
                            ->when(isset($this->evento) && $this->evento != "", function($q){
                                return $q->where('event', $this->evento);

                            })
                            ->when(isset($this->modelo) && $this->modelo != "", function($q){
                                return $q->where('auditable_type', $this->modelo);

                            })
                            ->orderBy($this->sort, $this->direction)
                            ->paginate($this->pagination);


        return view('livewire.admin.auditoria', compact('audits', 'usuarios'))->extends('layouts.admin');
    }

}
