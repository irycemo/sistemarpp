<?php

namespace App\Models;

use App\Http\Traits\ModelosTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FolioReal extends Model
{
    use HasFactory;
    use ModelosTrait;

    public function propietarios(){
        return $this->morphMany(Propietario::class, 'propietarioable');
    }

    public function movimientosRegistrales(){
        return $this->hasMany(MovimientoRegistral::class);
    }

    public function predio(){
        return $this->hasOne(Predio::class);
    }

}
