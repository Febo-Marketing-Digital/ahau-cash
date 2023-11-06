<?php

use App\Http\Controllers\BankController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LoanInstallmentController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\LoanV2Controller;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/loans-v2', [LoanV2Controller::class, 'index']);
// Route::get('/loans-v2/create', [LoanV2Controller::class, 'create']);
// Route::post('/loans-v2/create', [LoanV2Controller::class, 'store']);
// Route::get('/loans-v2/table', [LoanV2Controller::class, 'table']);
// Route::get('/loans-test', [LoanV2Controller::class, 'test']);

Route::get('/', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/staff-list', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff', [StaffController::class, 'create'])->name('staff.create');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/{user}', [StaffController::class, 'show'])->name('staff.show');
    Route::put('/staff/{user}/update', [StaffController::class, 'update'])->name('staff.update');
    Route::get('/staff/{user}/delete', [StaffController::class, 'delete'])->name('staff.delete');

    Route::get('/banks', [BankController::class, 'index'])->name('bank.index');
    Route::get('/bank', [BankController::class, 'create'])->name('bank.create');
    Route::post('/bank', [BankController::class, 'store'])->name('bank.store');
    Route::get('/bank/{bank}/delete', [BankController::class, 'delete'])->name('bank.delete');

    Route::get('/clients', [UserController::class, 'index'])->name('client.index');
    Route::get('/client', [UserController::class, 'create'])->name('client.create');
    Route::post('/client', [UserController::class, 'store'])->name('client.store');
    Route::get('/client/{user}', [UserController::class, 'edit'])->name('client.edit');
    Route::patch('/client/{user}', [UserController::class, 'update'])->name('client.update');
    Route::get('/client/{user}/delete', [UserController::class, 'delete'])->name('client.delete');
    Route::get('/client/{user}/address', [UserController::class, 'editAddress'])->name('client.edit.address');
    Route::patch('/client/{user}/address', [UserController::class, 'updateAddress'])->name('client.update.address');
    Route::get('/client/{user}/bank-details', [UserController::class, 'editBankDetails'])->name('client.edit.bankDetails');
    Route::patch('/client/{user}/bank-details', [UserController::class, 'updateBankDetails'])->name('client.update.bankDetails');

    Route::get('/loans', [LoanController::class, 'index'])->name('loan.index');
    Route::get('/loan', [LoanController::class, 'create'])->name('loan.create');
    Route::post('/loan', [LoanController::class, 'store'])->name('loan.store');
    Route::get('/loan/{uuid}', [LoanController::class, 'show'])->name('loan.show');
    Route::get('/loan/{loan}/delete', [LoanController::class, 'destroy'])->name('loan.delete');
    Route::patch('/loan/{loan}/update', [LoanController::class, 'update'])->name('loan.update');
    Route::post('/loan/{loan}/settled', [LoanController::class, 'settled'])->name('loan.settled');

    Route::get('/loan-installment/{id}', [LoanInstallmentController::class, 'show'])->name('loan.installment.show');
    Route::post('/loan-installment/{installment}/payment', [LoanInstallmentController::class, 'storeNotePayment'])->name('loan.installment.storeNotePayment');

    Route::get('/client/{user}/documentation', [DocumentationController::class, 'edit'])->name('documentation.edit');
    Route::put('/client/{user}/documentation', [DocumentationController::class, 'update'])->name('documentation.update');
    Route::delete('/client/{user}/documentation', [DocumentationController::class, 'destroy'])->name('documentation.destroy');

    Route::get('test', [LoanController::class, 'testPdfDownload']);
    Route::get('test/render/{loan}/contract', [TestController::class, 'renderNote']);
});

require __DIR__.'/auth.php';
