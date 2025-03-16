<?php

use App\Http\Controllers\Admin\AsistenciaController;
use Backpack\CRUD\app\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::any('/admin/register', function () {
    abort(404);
});


Route::get('/', function () {
    return redirect()->route('backpack.dashboard');
});


Route::get('/asistencia_estudiante/{token}', [AsistenciaController::class, 'mostrarFormulario'])->name('asistencia.form');
Route::post('/asistencia_estudiante/{token}', [AsistenciaController::class, 'registrarAsistencia'])->name('asistencia.registrar');


