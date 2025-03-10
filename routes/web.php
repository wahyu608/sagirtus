<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\KategoryController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\GuestController;

Route::get('/', [GuestController::class, 'index'])->name('users.index');
Route::get('/data', [GuestController::class, 'data'])->name('users.data');
Route::get('/datajson', [DataController::class, 'dataJson'])->name('datajson');
Route::get('/desajson', [DesaController::class, 'desaJson'])->name('desajson');
Route::get('/kategoryjson', [KategoryController::class, 'kategoryJson'])->name('kategoryjson');
Route::get('/admin/about', function(){
    return view('admin.about');
})->name('admin.about');
Route::get('/about', function(){
    return view('users.about');
})->name('guest.about');

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/dashboard', [MapController::class, 'data'])->name('dashboard');
    Route::get('/data', [DataController::class, 'index'])-> name('data');
    Route::put('/data/update/{id}', [DataController::class, 'update'])-> name('update.data');
    Route::post('/data/store', [DataController::class, 'store'])-> name('store.data');
    Route::delete('/data/delete/{id}', [DataController::class, 'destroy'])-> name('delete.data');
    Route::get('/desa', [DesaController::class, 'index'])-> name('desa');
    Route::put('/desa/update/{id}', [DesaController::class, 'update'])-> name('update.desa');
    Route::post('/desa/store', [DesaController::class, 'store'])-> name('store.desa');
    Route::delete('/desa/delete/{id}', [DesaController::class, 'destroy'])-> name('delete.desa');
    Route::get('/kategory', [KategoryController::class, 'index'])-> name('kategory');
    Route::put('/kategory/update/{id}', [KategoryController::class, 'update'])-> name('update.kategory');
    Route::post('/kategory/store', [KategoryController::class, 'store'])-> name('store.kategory');
    Route::delete('/kategory/delete/{id}', [KategoryController::class, 'destroy'])-> name('delete.kategory');
    Route::get('/about', function(){
        return view('admin.about');
    })->name('admin.about');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
