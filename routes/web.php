<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\MenuCategoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/auth/login');
});

Route::get('/auth/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/auth/login', [AuthController::class, 'login'])->name('admin.login.submit');

Route::middleware(['auth'])->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('admin.logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('tables', TableController::class);

    Route::resource('menu-categories', MenuCategoryController::class);

    Route::resource('menus', MenuController::class);

    Route::get('/order/table/{tableNumber}', [OrderController::class, 'showMenu'])->name('order.table');

    Route::post('/cart/add-ajax', [OrderController::class, 'addToCartAjax'])->name('cart.add.ajax');
    Route::post('/cart/remove-item', [OrderController::class, 'removeCartItem'])->name('cart.remove.item');
    Route::get('/cart/preview/{tableNumber}', [OrderController::class, 'getCartPreview'])->name('cart.preview');
    Route::post('/order/checkout/{tableNumber}', [OrderController::class, 'checkout'])->name('order.checkout');
    Route::get('/order/status/{order}', [OrderController::class, 'status'])->name('order.status');
    Route::get('/order/status/{order}/ajax', [OrderController::class, 'statusAjax']);

    Route::get('/order/status/partial/{orderId}', [OrderController::class, 'getStatusPartial'])->name('order.status.partial');

    Route::get('/admin/tables/status', [TransactionController::class, 'status'])->name('admin.tables.status');

    Route::get('/admin/kitchen/orders', [TransactionController::class, 'index'])->name('admin.kitchen.orders');
    Route::post('/admin/kitchen/order-items/{item}/update', [TransactionController::class, 'updateItemStatus'])->name('admin.kitchen.order-items.update');
    Route::get('/admin/orders/served', [TransactionController::class, 'served'])->name('admin.orders.served');
    Route::post('/admin/orders/{order}/pay', [TransactionController::class, 'pay'])->name('admin.orders.pay');
});
