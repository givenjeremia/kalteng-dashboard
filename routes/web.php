<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FileCategoriesController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CeilingController;
use App\Http\Controllers\EMonevController;
use App\Http\Controllers\IKPAScoreController;
use App\Http\Controllers\EPerformanceController;

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::middleware(['auth'])->group(function(){
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/dashboard/data', [HomeController::class, 'dataDashboard'])->name('dashboard.data');

    Route::resource('users', UserController::class);
    Route::resource('file-categories', FileCategoriesController::class);
    Route::resource('departements', DepartementController::class);
    Route::resource('performances', PerformanceController::class);
    Route::prefix('budgets')->group(function () {
        
        Route::resource('expenses', BudgetController::class);
        Route::resource('ceilings', CeilingController::class);
        Route::resource('e-monev', EmonevController::class);
        Route::resource('ikpa-score', IKPAScoreController::class);
        Route::resource('e-performance', EPerformanceController::class);




    });

    





});