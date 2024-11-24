<?php

use App\Http\Controllers\admin\AsistenciaController;
use Backpack\CRUD\app\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/asistencia_estudiante/{token}', [AsistenciaController::class, 'mostrarFormulario'])->name('asistencia.form');
Route::post('/asistencia_estudiante/{token}', [AsistenciaController::class, 'registrarAsistencia'])->name('asistencia.registrar');

Route::get('/', [LoginController::class, 'showLoginForm']);
