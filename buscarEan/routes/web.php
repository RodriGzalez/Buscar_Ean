<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MeanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EanController;
use App\Http\Controllers\SkuController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\Auth\RegisteredUserController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::middleware('auth')->group(function () {

    Route::post('/actualizar-ean', [EanController::class, 'actualizar'])->name('ean.actualizar');
    Route::get('/buscar', [MeanController::class, 'buscar'])
        ->middleware(['auth'])
        ->name('buscar');
    Route::get('/janis-update', [SkuController::class, 'index']);
    Route::post('/janis-update', [SkuController::class, 'update'])->name('janis.update');
    Route::get('/menu', [MenuController::class, 'menu'])->name('menu');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::group([
        'middleware' => function ($request, $next) {
            if (auth()->check() && auth()->user()->role === 'admin') {
                return $next($request);
            }
            return redirect()->route('menu')->with('error', 'No tienes permiso para registrar usuarios.');
        }
    ], function () {
        Route::get('register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])->name('register');
        Route::post('register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);
    });
});





require __DIR__ . '/auth.php';
