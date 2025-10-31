<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HospedajeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Aquí se definen las rutas principales de la aplicación.
| Se generan automáticamente todas las rutas CRUD para HospedajeController.
*/



Route::get('/', function () {
    return redirect()->route('hospedaje.index');
});

Route::resource('hospedaje', HospedajeController::class);
Route::get('/hospedaje/buscar', [HospedajeController::class, 'buscar'])->name('hospedaje.buscar');

// Rutas para mostrar documentos de hospedaje
Route::get('hospedaje/carta/{hospedaje}', [App\Http\Controllers\HospedajeController::class, 'verCarta'])
    ->name('hospedaje.verCarta');

Route::get('hospedaje/foto/{hospedaje}', [App\Http\Controllers\HospedajeController::class, 'verFoto'])
    ->name('hospedaje.verFoto');



