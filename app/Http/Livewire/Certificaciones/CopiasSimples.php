<?php

namespace App\Http\Livewire\Certificaciones;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Certificacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ComponentesTrait;
use App\Http\Services\SistemaTramites\SistemaTramitesService;
use App\Models\MovimientoRegistral;

class CopiasSimples extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public Certificacion $modelo_editar;
    public $observaciones;
    public $modalRechazar;

    protected function rules(){
        return [
            'modelo_editar.folio_carpeta_copias' => 'required|unique:certificacions,folio_carpeta_copias,' . $this->modelo_editar->id,
         ];
    }

    protected $validationAttributes  = [
        'modelo_editar.folio_carpeta_copias' => 'folio de carpeta'
    ];

    public function crearModeloVacio(){
        return Certificacion::make();
    }

    public function abrirModalEditar(Certificacion $modelo){

        $this->resetearTodo();
        $this->modal = true;
        $this->editar = true;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

    }

    public function abrirModalRechazar(Certificacion $modelo){

        $this->resetearTodo();
        $this->modalRechazar = true;
        $this->editar = true;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

    }

    public function finalizarSupervisor(Certificacion $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        if($this->modelo_editar->folio_carpeta_copias == null){

            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "EL campo folio de carpeta es obligatorio."]);
            return;

        }

        try {

            $this->modelo_editar->finalizado_en = now();

            $this->modelo_editar->actualizado_por = auth()->user()->id;

            $this->modelo_editar->movimientoRegistral->estado = 'concluido';

            $this->modelo_editar->movimientoRegistral->save();

            $this->modelo_editar->save();

            (new SistemaTramitesService())->finaliarTramite($this->modelo_editar->movimientoRegistral->tramite, 'concluido');

            $this->resetearTodo();

            $this->dispatchBrowserEvent('mostrarMensaje', ['success', "El trámite se finalizó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de copias simples por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function finalizar(){

        $this->validate();

        try{

            $this->modelo_editar->actualizado_por = auth()->user()->id;

            $this->modelo_editar->save();

            $this->dispatchBrowserEvent('imprimir_documento', ['documento' => $this->modelo_editar->id]);

            $this->dispatchBrowserEvent('mostrarMensaje', ['success', "El trámite se finalizó con éxito."]);

            $this->resetearTodo();

        } catch (\Throwable $th) {

            Log::error("Error al finalizar trámite de copias simples por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();
        }

    }

    public function rechazar(){

        $this->validate([
            'observaciones' => 'required'
        ]);

        try {

            DB::transaction(function (){

                $observaciones = auth()->user()->name . ' rechaza el ' . now() . ', con motivo: ' . $this->observaciones . '<|>';

                (new SistemaTramitesService())->rechazarTramite($this->modelo_editar->movimientoRegistral->tramite, $observaciones);

                $this->modelo_editar->movimientoRegistral->update(['estado' => 'rechazado']);

                $this->modelo_editar->actualizado_por = auth()->user()->id;

                $this->modelo_editar->observaciones = $this->modelo_editar->observaciones . $observaciones;

                $this->modelo_editar->save();

                $this->dispatchBrowserEvent('mostrarMensaje', ['success', "El trámite se rechazó con éxito."]);

                $this->resetearTodo();

            });

        } catch (\Throwable $th) {
            Log::error("Error al rechazar trámite de copias certificadas por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();
        }

    }

    public function reimprimir(Certificacion $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;


        try {

            $this->dispatchBrowserEvent('imprimir_documento', ['documento' => $this->modelo_editar->id]);

            $this->modelo_editar->reimpreso_en = now();

            $this->modelo_editar->actualizado_por = auth()->user()->id;

            $this->modelo_editar->save();

            $this->resetearTodo();

        } catch (\Throwable $th) {

            Log::error("Error al reimprimir trámite de copias certificadas por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatchBrowserEvent('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function mount(){

        array_push($this->fields, 'modalRechazar', 'observaciones');

        $this->modelo_editar = $this->crearModeloVacio();

    }

    public function render()
    {

        if(auth()->user()->hasRole('Supervisor Copias')){

            $copias = MovimientoRegistral::with('asignadoA', 'supervisor', 'actualizadoPor', 'certificacion.actualizadoPor')
                                            ->where(function($q){
                                                $q->whereHas('asignadoA', function($q){
                                                        $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                    })
                                                    ->orWhereHas('supervisor', function($q){
                                                        $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                    })
                                                    ->orWhere('solicitante', 'LIKE', '%' . $this->search . '%')
                                                    ->orWhere('tomo', 'LIKE', '%' . $this->search . '%')
                                                    ->orWhere('registro', 'LIKE', '%' . $this->search . '%')
                                                    ->orWhere('distrito', 'LIKE', '%' . $this->search . '%')
                                                    ->orWhere('seccion', 'LIKE', '%' . $this->search . '%')
                                                    ->orWhere('tramite', 'LIKE', '%' . $this->search . '%');
                                            })
                                            ->where('estado', 'nuevo')
                                            ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                $q->where('distrito', 2);
                                            })
                                            ->whereHas('certificacion', function($q){
                                                $q->where('servicio', 'DL14')
                                                    ->whereNull('finalizado_en');
                                            })
                                            ->whereRaw('DATE_ADD(`fecha_pago`, INTERVAL 2 DAY) <= NOW()')
                                            ->orderBy($this->sort, $this->direction)
                                            ->paginate($this->pagination);

        }elseif(auth()->user()->hasRole('Certificador')){

            $copias = MovimientoRegistral::with('asignadoA', 'supervisor', 'actualizadoPor', 'certificacion.actualizadoPor')
                                            ->where(function($q){
                                                $q->whereHas('asignadoA', function($q){
                                                        $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                    })
                                                    ->orWhereHas('supervisor', function($q){
                                                        $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                    })
                                                    ->orWhere('solicitante', 'LIKE', '%' . $this->search . '%')
                                                    ->orWhere('tomo', 'LIKE', '%' . $this->search . '%')
                                                    ->orWhere('registro', 'LIKE', '%' . $this->search . '%')
                                                    ->orWhere('distrito', 'LIKE', '%' . $this->search . '%')
                                                    ->orWhere('seccion', 'LIKE', '%' . $this->search . '%')
                                                    ->orWhere('tramite', 'LIKE', '%' . $this->search . '%');
                                            })
                                            ->where('estado', 'nuevo')
                                            ->where('usuario_asignado', auth()->user()->id)
                                            ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                                $q->where('distrito', 2);
                                            })
                                            ->whereHas('certificacion', function($q){
                                                $q->where('servicio', 'DL14')
                                                    ->whereNull('finalizado_en')
                                                    ->whereNull('folio_carpeta_copias');
                                            })
                                            ->whereRaw('DATE_ADD(`fecha_pago`, INTERVAL 2 DAY) <= NOW()')
                                            ->orderBy($this->sort, $this->direction)
                                            ->paginate($this->pagination);

        }else{

            $copias = MovimientoRegistral::with('asignadoA', 'supervisor', 'actualizadoPor', 'certificacion.actualizadoPor')
                                            ->where(function($q){
                                                $q->whereHas('asignadoA', function($q){
                                                        $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                    })
                                                    ->orWhereHas('supervisor', function($q){
                                                        $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                    })
                                                    ->orWhere('solicitante', 'LIKE', '%' . $this->search . '%')
                                                    ->orWhere('tomo', 'LIKE', '%' . $this->search . '%')
                                                    ->orWhere('registro', 'LIKE', '%' . $this->search . '%')
                                                    ->orWhere('distrito', 'LIKE', '%' . $this->search . '%')
                                                    ->orWhere('seccion', 'LIKE', '%' . $this->search . '%')
                                                    ->orWhere('tramite', 'LIKE', '%' . $this->search . '%');
                                            })
                                            ->whereHas('certificacion', function($q){
                                                $q->where('servicio', 'DL14');
                                            })
                                            ->orderBy($this->sort, $this->direction)
                                            ->paginate($this->pagination);

        }


        return view('livewire.certificaciones.copias-simples', compact('copias'))->extends('layouts.admin');
    }
}
