<?php

use App\Http\Livewire\Admin\Roles;
use App\Http\Livewire\Admin\Ranchos;
use App\Http\Livewire\Admin\Permisos;
use App\Http\Livewire\Admin\Usuarios;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Admin\Auditoria;
use App\Http\Livewire\Admin\Distritos;
use App\Http\Livewire\Admin\Tenencias;
use App\Http\Livewire\Admin\Municipios;
use App\Http\Controllers\ManualController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


Route::group(['middleware' => ['auth', 'esta.activo']], function(){

    Route::get('roles', Roles::class)->middleware('permission:Lista de roles')->name('roles');

    Route::get('permisos', Permisos::class)->middleware('permission:Lista de permisos')->name('permisos');

    Route::get('usuarios', Usuarios::class)->middleware('permission:Lista de usuarios')->name('usuarios');

    Route::get('distritos', Distritos::class)->middleware('permission:Lista de distritos')->name('distritos');

    Route::get('municipios', Municipios::class)->middleware('permission:Lista de municipios')->name('municipios');

    Route::get('tenencias', Tenencias::class)->middleware('permission:Lista de tenencias')->name('tenencias');

    Route::get('ranchos', Ranchos::class)->middleware('permission:Lista de ranchos')->name('ranchos');

    Route::get('auditoria', Auditoria::class)->middleware('permission:Auditoria')->name('auditoria');


});

Route::get('setpassword/{email}', [SetPasswordController::class, 'create'])->name('setpassword');
Route::post('setpassword', [SetPasswordController::class, 'store'])->name('setpassword.store');

Route::get('manual', ManualController::class)->name('manual');
