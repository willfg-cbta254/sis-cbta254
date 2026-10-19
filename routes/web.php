<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\BoletaController;
use App\Http\Controllers\EstrategiaDownloadController;
use App\Http\Controllers\FichaController;
use App\Http\Controllers\PagoReciboController;
use Illuminate\Support\Facades\Route;

Route::get('/__debug_boom', function () {
    throw new RuntimeException('boom');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/galeria', [BlogController::class, 'gallery'])->name('blog.gallery');

Route::get('/fichas/{ficha}/descargar', [FichaController::class, 'download'])->name('fichas.download');

Route::get('/pagos/{pago}/recibo', [PagoReciboController::class, 'download'])->middleware('auth')->name('pagos.recibo');
Route::get('/boletas/{alumno}', [BoletaController::class, 'download'])->middleware('auth')->name('boletas.download');

Route::get('/estrategias/{estrategia}/descargar', [EstrategiaDownloadController::class, 'download'])->middleware('auth')->name('estrategias.download');
Route::get('/estrategias/{estrategia}/ver', [EstrategiaDownloadController::class, 'stream'])->middleware('auth')->name('estrategias.stream');
