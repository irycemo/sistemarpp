<?php

namespace App\Http\Controllers;

use App\Models\MovimientoRegistral;
use Illuminate\Http\Request;

class ValidacionController extends Controller
{

    public function validar(MovimientoRegistral $movimientoRegistral){


        return view('validacion', compact('movimientoRegistral'));

    }
}
