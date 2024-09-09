<?php

use App\Http\Controllers\AcpController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StrausController;
use App\Http\Controllers\StrausSurveiController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

// users registrasi
Route::get('/', [UsersController::class, 'index'])->name('users.index');
Route::post('/users/store', [UsersController::class, 'store'])->name('users.store');

// straus survei
Route::get('/straus-survei', [StrausSurveiController::class, 'index'])->name('straus-survei.index');
Route::post('/straus-survei/store', [StrausSurveiController::class, 'store'])->name('straus-survei.store');



// login

Route::get('/login', [UsersController::class, 'auth'])->name('login');
Route::post('/login', [UsersController::class, 'login'])->name('users.proses_login');

Route::group(['middleware' => ['auth', 'IsAdmin']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [UsersController::class, 'logout'])->name('users.logout');

    Route::get('/export-user-answers', [DashboardController::class, 'exportExcel'])->name('export-user-answers');

    Route::get('/straus', [StrausController::class, 'index'])->name('straus.index');
    Route::get('/straus/create', [StrausController::class, 'create'])->name('straus.create');
    Route::post('/straus/store', [StrausController::class, 'store'])->name('straus.store');
    Route::get('/straus/edit/{id}', [StrausController::class, 'edit'])->name('straus.edit');
    Route::put('/straus/update/{id}', [StrausController::class, 'update'])->name('straus.update');
    Route::delete('/straus/delete/{id}', [StrausController::class, 'destroy'])->name('straus.destroy');


    Route::get('/acp', [AcpController::class, 'index'])->name('acp.index');
    Route::get('/acp/create', [AcpController::class, 'create'])->name('acp.create');
    Route::post('/acp/store', [AcpController::class, 'store'])->name('acp.store');
});
