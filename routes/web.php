<?php

use App\Http\Controllers\Admin\AdminInventoryController;
use App\Http\Controllers\Admin\AdminRestoreController;
use App\Http\Controllers\Admin\AdminTransactionController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DeliveriesController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReservationController;
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

    Route::get('/cashier/dashboard', [InventoryController::class, 'dashboard'])
    ->middleware('role:Cashier')
    ->name('cashier.dashboard');

    Route::post('/cashier/payment', [SaleController::class, 'showPayment'])->name('cashier.payment');
    Route::post('/sales/store', [SaleController::class, 'store'])->name('sales.store');
    Route::get('sales/download/{id}', [SaleController::class, 'downloadReceipt'])->name('sales.download');
    Route::post('/feedback/{sale}', [SaleController::class, 'storeFeedback'])->name('feedback.store');
    Route::get('/cashier/inventory', [InventoryController::class, 'index'])->name('cashier.inventory');
    Route::get('/cashier/history', [SaleController::class, 'history'])->name('cashier.history');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
    Route::patch('/notifications/{notification}/resolve', [NotificationController::class, 'resolve'])->name('notifications.resolve');


    



    


    Route::get('/admin/reservations', [ReservationController::class, 'index'])->name('admin.reservations')->middleware('role:Admin');
    Route::post('/admin/reservations/restore/{id}', [ReservationController::class, 'restore'])->name('admin.reservations.restore')->middleware('role:Admin');
    Route::post('/admin/deliveries/{delivery}/approve', [DeliveriesController::class, 'approve'])->name('admin.deliveries.approve')
           ->middleware('role:Admin');
});

Route::middleware(['auth', 'role:Storekeeper'])->prefix('storekeeper')->name('storekeeper.')->group(function () {
    Route::get('/dashboard', [InventoryController::class, 'storekeeperDashboard'])->name('dashboard');
    Route::get('/flagged', [InventoryController::class, 'flaggedItems'])->name('flagged');
    Route::get('/history', [InventoryController::class, 'restockHistory'])->name('history');
    Route::post('/inventory/{item}/restock', [InventoryController::class, 'restock'])->name('restock');
    Route::resource('deliveries', DeliveriesController::class)->only(['index', 'create', 'store']);
    Route::get('/deliveries/{delivery}/pdf', [DeliveriesController::class, 'downloadPDF'])->name('deliveries.pdf');
});


Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/inventory/{id}/update-price', [AdminInventoryController::class, 'updatePrice'])->name('inventory.updatePrice');
    Route::post('/inventory/bulk-discount', [AdminInventoryController::class, 'applyBulkDiscount'])->name('inventory.discount');
    Route::post('/inventory/{id}/threshold', [AdminInventoryController::class,'adjustThreshold'])->name('inventory.threshold');


    //Transaction Oversight
    Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('transactions.index');
    
    
    //Booking & restore
    Route::get('/bookings', [AdminRestoreController::class, 'index'])->name('bookings.index');
    Route::post('/restore/{id}', [AdminRestoreController::class, 'restoreToStock'])->name('restore.stock');

    //Broadcast Notifications
    Route::post('/broadcast', [NotificationController::class, 'sendBroadcast'])->name('broadcast');

});