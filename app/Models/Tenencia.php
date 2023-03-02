<?php

namespace App\Models;

use App\Http\Traits\ModelosTrait;
use App\Models\Distrito;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tenencia extends Model implements Auditable
{
    use HasFactory;
    use ModelosTrait;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['nombre', 'distrito_id', 'creado_por', 'actualizado_por'];

    public function distrito(){
        return $this->belongsTo(Distrito::class);
    }

}
