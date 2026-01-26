<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('login');

//Login logic
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class,'logout'])->name('logout');

//RBAC - Protected Dashboard Routes

Route::middleware(['auth'])->group(function () {

    Route::get('/admin/dashboard', function(){
        return "Welcome Admin ". auth()->user()->user_id_alias;
    })->middleware('role:Admin');

    Route::get('/storekeeper/dashboard', function(){
        return "Welcome StoreKeeper".auth()->user()->user_id_alias;
    })->middleware('role:Storekeeper');
    Route::get('/cashier/dashboard', function(){
        return "Welcome Cashier".auth()->user()->user_id_alias;
    })->middleware('role:Cashier');
});