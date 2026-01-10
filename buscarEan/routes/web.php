<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MeanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EanController;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::post('/actualizar-ean', [EanController::class, 'actualizar'])->name('ean.actualizar');

Route::get('/buscar', [MeanController::class, 'buscar'])
    ->middleware(['auth'])
    ->name('buscar');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
