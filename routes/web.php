<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FisrolController;
use App\Http\Controllers\SatrolController;
use App\Http\Controllers\KiyotaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/fisrol-form', [FisrolController::class, 'form'])->name('fisrol.form');
Route::post('/fisrol-hasil', [FisrolController::class, 'hasil'])->name('fisrol.hasil');
Route::post('/fisrol-unduh', [FisrolController::class, 'unduhPpt'])->name('fisrol.unduh');

Route::get('/satrol-form', [SatrolController::class, 'form'])->name('satrol.form');
Route::post('/satrol-hasil', [SatrolController::class, 'hasil'])->name('satrol.hasil');
Route::post('/satrol-unduh', [SatrolController::class, 'unduhPpt'])->name('satrol.unduh');

Route::get('/kiyota-form', [KiyotaController::class, 'form'])->name('kiyota.form');
Route::post('/kiyota-hasil', [KiyotaController::class, 'hasil'])->name('kiyota.hasil');
Route::post('/kiyota-unduh', [KiyotaController::class, 'unduhPpt'])->name('kiyota.unduh');
