<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Vue SPA Frontend
| Laravel Backend API ada di routes/api.php
*/

Route::redirect('/', '/dashboard');
Route::view('/dashboard', 'spa')->name('dashboard');
Route::view('/login', 'spa')->name('login');

/*
|--------------------------------------------------------------------------
| SPA Catch All Route
|--------------------------------------------------------------------------
| Semua halaman frontend diarahkan ke resources/views/spa.blade.php
*/

Route::view('/{any}', 'spa')
    ->where('any', '^(?!api).*$')
    ->name('spa');

/*
|--------------------------------------------------------------------------
| Optional Laravel Profile (jika masih dipakai)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';