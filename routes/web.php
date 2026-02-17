<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GraduationController;
use App\Http\Middleware\EnsureFaculdadeAuthenticated;
use App\Http\Middleware\RedirectIfFaculdadeAuthenticated;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(RedirectIfFaculdadeAuthenticated::class)->group(function () {
    Route::get('/', function () {
        return Inertia::render('Login');
    })->name('login');

    Route::post('/login-faculdade', [AuthController::class, 'login'])
    ->name('login.faculdade');
});

Route::middleware(EnsureFaculdadeAuthenticated::class)->group(function () {
    Route::get('/dashboard', [GraduationController::class, 'list_all_graduation'])
        ->name('dashboard');
});


