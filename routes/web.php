<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BoletaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductoController;


Route::get('/', function () {
    return redirect()->route('login');
});


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware('auth')->group(function () {
    
    Route::resource('productos', ProductoController::class);
    
    Route::get('/boletas', [BoletaController::class, 'index'])->name('boletas.index');
    Route::get('/boletas/create', [BoletaController::class, 'create'])->name('boletas.create');
    Route::post('/boletas', [BoletaController::class, 'store'])->name('boletas.store');
    Route::get('/boletas/{boleta}/download', [BoletaController::class, 'downloadTxt'])->name('boletas.download');

    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';