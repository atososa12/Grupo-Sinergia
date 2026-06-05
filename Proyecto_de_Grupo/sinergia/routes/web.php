<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocioController;
use App\Http\Controllers\MembresiaController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\ClaseController;
use App\Http\Controllers\AsistenciaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('socios', SocioController::class);
    Route::resource('membresias', MembresiaController::class);
    Route::resource('inscripciones', InscripcionController::class);
    Route::resource('pagos', PagoController::class);
    Route::resource('instructores', InstructorController::class);
    Route::resource('clases', ClaseController::class);
    Route::resource('asistencias', AsistenciaController::class);

});

require __DIR__.'/auth.php';