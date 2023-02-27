<?php

namespace App\Models;

use App\Http\Traits\ModelosTrait;
use App\Models\Rancho;
use App\Models\Tenencia;
use App\Models\Municipio;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Distrito extends Model
{
    use HasFactory;
    use ModelosTrait;

    protected $fillable = ['clave', 'nombre', 'creado_por', 'actualizado_por'];

    public function municipios(){
        return $this->hasMany(Municipio::class);
    }

    public function tenencias(){
        return $this->hasMany(Tenencia::class);
    }

    public function ranchos(){
        return $this->hasMany(Rancho::class);
    }

}
