<?php

namespace App\Models;

use App\Http\Traits\ModelosTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Colindancia extends Model
{
    use HasFactory;
    use ModelosTrait;

    protected $fillable = ['predio_id', 'viento', 'longitud', 'descripcion', 'creado_por', 'actualizado_por'];

}
