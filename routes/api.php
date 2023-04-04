<?php

use App\Http\Controllers\Api\MovimientoRegistralController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('movimiento_registral', [MovimientoRegistralController::class, 'store']);

Route::post('actualizar_registral', [MovimientoRegistralController::class, 'update']);

Route::fallback(function(){
    return response()->json([
        'message' => 'Página no encontrada.'], 404);
});
