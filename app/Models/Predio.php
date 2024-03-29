<?php

namespace App\Models;

use App\Models\Propietario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Predio extends Model
{

    use HasFactory;

    public function colindancias(){
        return $this->morphMany(Colindancia::class, 'colindanciaable');
    }

}
