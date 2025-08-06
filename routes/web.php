<?php

use App\Http\Controllers\App\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CRM\DealController;
use App\Http\Controllers\CRM\PipelineStageController;
use App\Http\Controllers\ProjectManagement\ProjectController;
use App\Http\Controllers\ProjectManagement\ProjectProgressController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    Route::get('register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth'])
    ->prefix('crm')
    ->group(function () {
        Route::get('/contacts', [\App\Http\Controllers\CRM\ContactController::class, 'index'])->name('contacts.index');
        Route::get('/contacts/create', [\App\Http\Controllers\CRM\ContactController::class, 'create'])->name('contacts.create');
        Route::post('/contacts/store', [\App\Http\Controllers\CRM\ContactController::class, 'store'])->name('contacts.store');
        Route::get('/contacts/{contact}/edit', [\App\Http\Controllers\CRM\ContactController::class, 'edit'])->name('contacts.edit');
        Route::put('/contacts/{contact}', [\App\Http\Controllers\CRM\ContactController::class, 'update'])->name('contacts.update');
        Route::delete('/contacts/{contact}', [\App\Http\Controllers\CRM\ContactController::class, 'destroy'])->name('contacts.destroy');

        Route::get('/pipeline', [PipelineStageController::class, 'index'])->name('pipeline.index');
        Route::get('/pipeline/create', [PipelineStageController::class, 'create'])->name('pipeline.create');
        Route::post('/pipeline/store', [PipelineStageController::class, 'store'])->name('pipeline.store');
        Route::get('/pipeline/{stages}/edit', [PipelineStageController::class, 'edit'])->name('pipeline.edit');
        Route::put('/pipeline/{stages}', [PipelineStageController::class, 'update'])->name('pipeline.update');
        Route::delete('/pipeline/{stages}', [PipelineStageController::class, 'destroy'])->name('pipeline.destroy');

        Route::get('/deal', [DealController::class, 'index'])->name('deal.index');
        Route::get('/deal/create', [DealController::class, 'create'])->name('deal.create');
        Route::post('/deal/store', [DealController::class, 'store'])->name('deal.store');
        Route::get('/deal/{deal}/edit', [DealController::class, 'edit'])->name('deal.edit');
        Route::put('/deal/{deal}', [DealController::class, 'update'])->name('deal.update');
        Route::delete('/deal/{deal}', [DealController::class, 'destroy'])->name('deal.destroy');
        Route::get('/deal/kanban', [DealController::class, 'kanban'])->name('deal.kanban');
        Route::post('/deal/{deal}/move', [DealController::class, 'move'])->name('deal.move');

        Route::prefix('project-management')->group(function () {
            Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');
            Route::get('projects/create', [ProjectController::class, 'create'])->name('projects.create');
            Route::post('projects/store', [ProjectController::class, 'store'])->name('projects.store');
            Route::get('projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
            Route::put('projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
            Route::delete('projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

            Route::get('projects/{project}/progress', [ProjectProgressController::class, 'index'])->name('projects.progress.index');
            Route::post('projects/{project}/progress', [ProjectProgressController::class, 'store'])->name('projects.progress.store');
        });
    });

// Default redirect
Route::get('/', fn() => redirect()->route('login'));
