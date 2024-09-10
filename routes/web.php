<?php

use App\Http\Controllers\AcpController;
use App\Http\Controllers\AcpSurveiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StrausController;
use App\Http\Controllers\StrausSurveiController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

// Users routes
Route::get('/', [UsersController::class, 'index'])->name('users.index'); //register
Route::post('/users/store', [UsersController::class, 'store'])->name('users.store');
Route::get('/login', [UsersController::class, 'auth'])->name('login');
Route::post('/login', [UsersController::class, 'login'])->name('users.proses_login');

// Straus Survei routes


Route::prefix('straus-survei')->name('straus-survei.')->group(function () {
    Route::get('/', [StrausSurveiController::class, 'index'])->name('index');
    Route::post('/store', [StrausSurveiController::class, 'store'])->name('store');
    Route::get('/completion-options', [StrausSurveiController::class, 'showCompletionOptions'])->name('completion-options');
});
Route::get('/acp-survei', [AcpSurveiController::class, 'index'])->name('acp-survei.index');
Route::post('/acp-survei/store', [AcpSurveiController::class, 'store'])->name('acp-survei.store');



// Admin routes
Route::middleware(['auth', 'IsAdmin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [UsersController::class, 'logout'])->name('users.logout');
    Route::get('/export-user-answers', [DashboardController::class, 'exportExcel'])->name('export-user-answers');

    // Straus CRUD
    Route::prefix('straus')->name('straus.')->group(function () {
        Route::get('/', [StrausController::class, 'index'])->name('index');
        Route::get('/create', [StrausController::class, 'create'])->name('create');
        Route::post('/store', [StrausController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [StrausController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [StrausController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [StrausController::class, 'destroy'])->name('destroy');
    });

    // ACP CRUD
    Route::prefix('acp')->name('acp.')->group(function () {
        Route::get('/', [AcpController::class, 'index'])->name('index');
        Route::get('/create', [AcpController::class, 'create'])->name('create');
        Route::post('/store', [AcpController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [AcpController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [AcpController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [AcpController::class, 'destroy'])->name('destroy');
    });
});
