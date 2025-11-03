<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrispevekController;
Route::get('/', function () {
    return view('welcome');
});

Route::post('/prispevky', [PrispevekController::class, 'store'])->name('prispevky.store');

Route::post('/prispevky/{prispevek}/like', [PrispevekController::class, 'like'])->name('prispevky.like');

Route::post('/prispevky/{prispevek}/komentar', [PrispevekController::class, 'commentStore'])
    ->middleware('auth')
    ->name('prispevky.comment');

Route::get('/', [PrispevekController::class, 'index'])->name('welcome');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
