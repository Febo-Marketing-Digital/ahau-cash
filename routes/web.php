<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/clients', [UserController::class, 'index'])->name('client.index');
    Route::get('/client', [UserController::class, 'create'])->name('client.create');
    Route::post('/client', [UserController::class, 'store'])->name('client.store');
    Route::get('/client/{user}', [UserController::class, 'edit'])->name('client.edit');
    Route::patch('/client/{user}', [UserController::class, 'update'])->name('client.update');

    Route::get('/loans', [LoanController::class, 'index'])->name('loan.index');
    Route::get('/loan', [LoanController::class, 'create'])->name('loan.create');
    Route::get('/loan', [LoanController::class, 'create'])->name('loan.create');
    Route::post('/loan', [LoanController::class, 'store'])->name('loan.store');

    Route::get('/client/{user}/documentation', [DocumentationController::class, 'edit'])->name('documentation.edit');
    Route::put('/client/{user}/documentation', [DocumentationController::class, 'update'])->name('documentation.update');

    Route::get('test', [LoanController::class, 'testPdfDownload']);
});

require __DIR__.'/auth.php';
