<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\MovimientoRegistralController;
use App\Models\Certificacion;
use App\Models\MovimientoRegistral;
use Illuminate\Console\Command;

class ReasignarUsuario extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reasignar:usuario';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tarea programada para reasignar certificador a las certificaciones que han llegado a su fecha de elaboración sin atenderse';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $ids = Certificacion::whereHas('movimientoRegistral', function($q){
                                                                            $q->where('estado', 'nuevo')
                                                                                ->where('fecha_entrega', '<=', now()->toDateString());
                                                                        })
                                                                        ->pluck('movimiento_registral_id');


        foreach($ids as $id){

            $movimientoRegistral = MovimientoRegistral::findOrFail($id);

            $nuevoUsuario = (new MovimientoRegistralController())->obtenerUsuarioAsignado(
                $movimientoRegistral->certificacion->servicio,
                $movimientoRegistral->getRawOriginal('distrito'),
                $movimientoRegistral->solicitante,
                $movimientoRegistral->tipo_servicio,
                false
            );

            while($nuevoUsuario == $movimientoRegistral->usuario_asignado){

                $nuevoUsuario = (new MovimientoRegistralController())->obtenerUsuarioAsignado(
                                                                                            $movimientoRegistral->certificacion->servicio,
                                                                                            $movimientoRegistral->getRawOriginal('distrito'),
                                                                                            $movimientoRegistral->solicitante,
                                                                                            $movimientoRegistral->tipo_servicio,
                                                                                            true
                                                                                        );

            }

            if($nuevoUsuario != $movimientoRegistral->usuario_asignado){

                $movimientoRegistral->update(['usuario_asignado' => $nuevoUsuario]);

            }

        }

    }
}
