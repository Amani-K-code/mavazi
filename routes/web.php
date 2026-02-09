<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', function(){
    return redirect()->route('home', ['#login-section']);
})->name('login');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
Route::get('/register/success',function() {
    return view('auth.register-success');
    })->name('register.success');

//Login logic
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class,'logout'])->name('logout');

//RBAC - Protected Dashboard Routes

Route::middleware(['auth'])->group(function () {

    Route::get('/admin/dashboard', function(){
        return view('admin.dashboard');
    })->middleware('role:Admin');

    Route::get('/storekeeper/dashboard', function(){
        return view('storekeeper.dashboard');
    })->middleware('role:Storekeeper');
    Route::get('/cashier/dashboard', [InventoryController::class, 'dashboard'])
    ->middleware('role:Cashier')
    ->name('cashier.dashboard');
    Route::post('/cashier/payment', [SaleController::class, 'showPayment'])->name('cashier.payment');
    Route::post('/sales/store', [SaleController::class, 'store'])->name('sales.store');
    Route::get('sales/download/{id}', [SaleController::class, 'downloadReceipt'])->name('sales.download');
    Route::post('/feedback/{sale}', [SaleController::class, 'storeFeedback'])->name('feedback.store');
});