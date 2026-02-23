<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GraduationController;
use App\Http\Controllers\ProfileController;
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

    Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

    Route::get('/profile', [ProfileController::class, 'profile'])
    ->name('profile');

    Route::get('/subjects/{idUsuarioCurso}/{idCurso}', [GraduationController::class, 'subjects'])
    ->name('subjects');

    Route::get('/activities/{id}/{idSalaVirtual}/{type}',[GraduationController::class, 'activities'])
    ->name('activities');

    Route::get('/activity_attempts/{cId}', [GraduationController::class, 'activity_attempts'])
    ->name('activity_attempts');

    Route::get('/answer_activity/{cID}', [GraduationController::class, 'answer_activity'])
    ->name('answer_activity');

    Route::post('/activities/{activity}/complete', [GraduationController::class , 'handleFormSubmit'])
    ->name('handleFormSubmit');

    Route::post('/selected_alternative/{alternativeId}', [GraduationController::class, 'selected_alternative'])
    ->name('selected_alternative');

    Route::post('/answer_activity/{ai}/{cID}', [GraduationController::class, 'answer_activity_ai'])
    ->name('answer_activity_ai');
});
