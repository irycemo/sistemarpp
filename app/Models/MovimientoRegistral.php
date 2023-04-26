<?php

namespace App\Models;

use App\Models\Certificacion;
use App\Http\Traits\ModelosTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MovimientoRegistral extends Model
{
    use HasFactory;
    use ModelosTrait;

    protected $fillable = [
        'monto',
        'estado',
        'predio_id',
        'folio_real',
        'tomo',
        'tomo_bis',
        'registro',
        'registro_bis',
        'tramite',
        'fecha_prelacion',
        'tipo_servicio',
        'solicitante',
        'seccion',
        'distrito',
        'usuario_asignado',
        'usuario_supervisor',
        'fecha_entrega',
        'actualizado_por'
    ];

    protected $casts = [
        'fecha_entrega' => 'date',
        'fecha_prelacion' => 'datetime'
    ];

    public function getEstadoColorAttribute()
    {
        return [
            'nuevo' => 'blue-400',
            'concluido' => 'gray-400',
            'rechazado' => 'red-400',
        ][$this->estado] ?? 'gray-400';
    }

    public function certificacion(){
        return $this->hasOne(Certificacion::class);
    }

    public function supervisor(){
        return $this->belongsTo(User::class, 'usuario_supervisor');
    }

    public function asignadoA(){
        return $this->belongsTo(User::class, 'usuario_asignado');
    }

}
