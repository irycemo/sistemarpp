<?php

namespace App\Console\Commands;

use App\Models\Certificacion;
use Illuminate\Console\Command;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Http\Services\SistemaTramites\SistemaTramitesService;

class ExpirarConsultas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expirar:consultas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tarea programada para concluir trámites de consulta';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        try {

            $ids = Certificacion::whereHas('movimientoRegistral', function($q){
                                                                                $q->where('estado', 'nuevo');
                                                                            })
                                                                            ->whereIn('servicio', ['DC92', 'DC91', 'DC90'])
                                                                            ->pluck('movimiento_registral_id');

            foreach ($ids as $id) {

                $movimiento = MovimientoRegistral::findOrFail($id);

                $movimiento->update(['estado' => 'expirado']);

                (new SistemaTramitesService())->finaliarTramite($movimiento->tramite, 'expirado');

            }

        } catch (\Throwable $th) {
            Log::error("Error al concluir trámites de consulta en tarea programada. " . $th);
        }

    }
}
