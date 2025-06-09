<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StandupController;
use App\Http\Controllers\TaskCategoryController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::resource('tasks', TaskController::class);
    Route::resource('task-categories', TaskCategoryController::class);
    Route::resource('daily-standup', StandupController::class);
    Route::resource('teams', TeamController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
