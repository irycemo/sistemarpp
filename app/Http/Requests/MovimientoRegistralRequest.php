<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovimientoRegistralRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'folio_real' => 'nullable',
            'monto' => 'required',
            'solicitante' => 'required',
            'nombre_solicitante' => 'required',
            'tramite' => 'required',
            'fecha_prelacion' => 'required',
            'tipo_servicio' => 'required',
            'seccion' => 'required',
            'distrito' => 'required',
            'fecha_entrega' => 'required',
            'fecha_pago' => 'nullable',
            'categoria_servicio' => 'required',
            'servicio' => 'required',
            'numero_oficio' => 'nullable',
            'folio_real' => 'nullable',
            'tomo' => 'nullable',
            'tomo_bis' => 'nullable',
            'registro' => 'nullable',
            'registro_bis' => 'nullable',
            'numero_paginas' => 'nullable',
            'numero_inmuebles' => 'nullable',
            'numero_propiedad' => 'nullable',
            'numero_escritura' => 'nullable',
            'numero_notaria' => 'nullable',
            'valor_propiedad' => 'nullable',
            'movimiento_registral' => 'nullable'
        ];
    }
}
