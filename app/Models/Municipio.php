<?php

namespace App\Models;

use App\Http\Traits\ModelosTrait;
use App\Models\Distrito;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Municipio extends Model
{
    use HasFactory;
    use ModelosTrait;

    protected $fillable = ['nombre', 'distrito_id', 'creado_por', 'actualizado_por'];

    public function distrito(){
        return $this->belongsTo(Distrito::class);
    }
}
