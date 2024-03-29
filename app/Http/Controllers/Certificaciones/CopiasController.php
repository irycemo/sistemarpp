<?php

namespace App\Http\Controllers\Certificaciones;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Certificacion;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Luecano\NumeroALetras\NumeroALetras;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;


class CopiasController extends Controller
{

    public function copiaCertificada(Certificacion $certificacion){

        $certificacion->load('movimientoRegistral');

        $formatter = new NumeroALetras();

        $director = Str::upper(User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Director');
        })->first()->name);

        $distrito = Str::upper($certificacion->movimientoRegistral->distrito);

        $registro = $certificacion->movimientoRegistral->registro;

        $registro_letras = $formatter->toWords($registro);

        $tomo = $certificacion->movimientoRegistral->tomo;

        $tomo_letras = $formatter->toWords($tomo);

        $paginas = $certificacion->numero_paginas;

        $paginas_letras = $formatter->toWords($paginas);

        $solicitante = Str::upper($certificacion->movimientoRegistral->solicitante);

        $now = now()->locale('es');

        $hora = $now->format('H');

        $hora_letras = $formatter->toWords($hora);

        $minutos = $now->format('i');

        $minutos_letras = $formatter->toWords($minutos);

        $dia = $now->format('d');

        $dia_letras = $formatter->toWords($dia);

        $mes = Str::upper($now->monthName);

        $año = $now->format('Y');

        $año_letras = $formatter->toWords($año);

        $numero_control = $certificacion->movimientoRegistral->tramite;

        $superviso = Str::upper($certificacion->movimientoRegistral->supervisor->name);

        $elaboro = Str::upper($certificacion->movimientoRegistral->asignadoA->name);

        $folio_carpeta = $certificacion->folio_carpeta_copias;

        $derechos = $certificacion->movimientoRegistral->monto;

        $fecha_entrega = $certificacion->movimientoRegistral->fecha_entrega;

        $tipo_servicio = Str::upper($certificacion->movimientoRegistral->tipo_servicio);

        $seccion = Str::upper($certificacion->movimientoRegistral->seccion);

        $qr = $this->generadorQr();

        $numero_oficio = $certificacion->movimientoRegistral->numero_oficio;

        if(auth()->user()->hasRole(['Certificador Oficialia', 'Certificador Juridico'])){

            $pdf = Pdf::loadView('certificaciones.copiaCertificadaOficialia', compact(
                'distrito',
                'director',
                'registro_letras',
                'registro',
                'tomo',
                'tomo_letras',
                'paginas',
                'paginas_letras',
                'solicitante',
                'hora',
                'hora_letras',
                'minutos',
                'minutos_letras',
                'dia',
                'dia_letras',
                'año',
                'año_letras',
                'mes',
                'numero_control',
                'numero_oficio',
                'superviso',
                'elaboro',
                'folio_carpeta',
                'derechos',
                'fecha_entrega',
                'tipo_servicio',
                'seccion',
                'qr'
            ));

        }else{

            $pdf = Pdf::loadView('certificaciones.copiaCertificada', compact(
                'distrito',
                'director',
                'registro_letras',
                'registro',
                'tomo',
                'tomo_letras',
                'paginas',
                'paginas_letras',
                'solicitante',
                'hora',
                'hora_letras',
                'minutos',
                'minutos_letras',
                'dia',
                'dia_letras',
                'año',
                'año_letras',
                'mes',
                'numero_control',
                'superviso',
                'elaboro',
                'folio_carpeta',
                'derechos',
                'fecha_entrega',
                'tipo_servicio',
                'seccion',
                'qr'
            ));

        }

        return $pdf->stream('documento.pdf');

    }

    public function copiaSimple(Certificacion $certificacion){

        $certificacion->load('movimientoRegistral');

        $formatter = new NumeroALetras();

        $director = Str::upper(User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Director');
        })->first()->name);

        $distrito = Str::upper($certificacion->movimientoRegistral->distrito);

        $registro = $certificacion->movimientoRegistral->registro;

        $registro_letras = $formatter->toWords($registro);

        $tomo = $certificacion->movimientoRegistral->tomo;

        $tomo_letras = $formatter->toWords($tomo);

        $paginas = $certificacion->numero_paginas;

        $paginas_letras = $formatter->toWords($paginas);

        $solicitante = Str::upper($certificacion->movimientoRegistral->solicitante);

        $now = now()->locale('es');

        $hora = $now->format('H');

        $hora_letras = $formatter->toWords($hora);

        $minutos = $now->format('i');

        $minutos_letras = $formatter->toWords($minutos);

        $dia = $now->format('d');

        $dia_letras = $formatter->toWords($dia);

        $mes = Str::upper($now->monthName);

        $año = $now->format('Y');

        $año_letras = $formatter->toWords($año);

        $numero_control = $certificacion->movimientoRegistral->tramite;

        $superviso = Str::upper($certificacion->movimientoRegistral->supervisor->name);

        $elaboro = Str::upper($certificacion->movimientoRegistral->asignadoA->name);

        $folio_carpeta = $certificacion->folio_carpeta_copias;

        $derechos = $certificacion->movimientoRegistral->monto;

        $fecha_entrega = $certificacion->movimientoRegistral->fecha_entrega;

        $tipo_servicio = Str::upper($certificacion->movimientoRegistral->tipo_servicio);

        $seccion = Str::upper($certificacion->movimientoRegistral->seccion);

        $qr = $this->generadorQr();

        $pdf = Pdf::loadView('certificaciones.copiaSimple', compact(
            'distrito',
            'director',
            'registro_letras',
            'registro',
            'tomo',
            'tomo_letras',
            'paginas',
            'paginas_letras',
            'solicitante',
            'hora',
            'hora_letras',
            'minutos',
            'minutos_letras',
            'dia',
            'dia_letras',
            'año',
            'año_letras',
            'mes',
            'numero_control',
            'superviso',
            'elaboro',
            'folio_carpeta',
            'derechos',
            'fecha_entrega',
            'tipo_servicio',
            'seccion',
            'qr'
        ));

        return $pdf->stream('documento.pdf');

    }

    public function generadorQr(){

        $result = Builder::create()
                            ->writer(new PngWriter())
                            ->writerOptions([])
                            ->data('https://irycem.michoacan.gob.mx/')
                            ->encoding(new Encoding('UTF-8'))
                            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
                            ->size(100)
                            ->margin(0)
                            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
                            ->labelText('Escanea para verificar')
                            ->labelFont(new NotoSans(7))
                            ->labelAlignment(new LabelAlignmentCenter())
                            ->validateResult(false)
                            ->build();

        return $result->getDataUri();
    }

}
