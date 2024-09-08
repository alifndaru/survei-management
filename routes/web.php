<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StrausController;
use App\Http\Controllers\StrausSurveiController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


// users

Route::get('/', [UsersController::class, 'index']);
Route::post('/users/store', [UsersController::class, 'store'])->name('users.store');

// straus survei
Route::get('/straus-survei', [StrausSurveiController::class, 'index'])->name('straus-survei.index');
Route::post('/straus-survei/store', [StrausSurveiController::class, 'store'])->name('straus-survei.store');





// admin
Route::get('/dashboard', [DashboardController::class, 'index']);


Route::get('/straus', [StrausController::class, 'index']);
Route::get('/straus/create', [StrausController::class, 'create'])->name('straus.create');
Route::post('/straus/store', [StrausController::class, 'store'])->name('straus.store');
