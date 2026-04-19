<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\DashboardController;
Route::get('/', ['middleware' => 'guest', function()
{
    return redirect('/login')->with('danger','YOUR ARE ALREADY LOG IN');
}]);

Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');

Auth::routes([
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
    'confirm'=>false, // Password Confirm
]);

Route::group(['middleware' => ['auth', 'IsActive']], function () {
    Route::group(['middleware' => 'IsAdmin'], function () {
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::post('/categories/update', [CategoryController::class, 'update'])->name('categories.update');
        Route::post('/categories/destroy', [CategoryController::class, 'destroy'])->name('categories.destroy'); 

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::post('/users/update', [UserController::class, 'update'])->name('users.update');
        Route::post('/users/destroy', [UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/users/{user_id}/security-questions', [UserController::class, 'getSecurityQuestions'])->name('users.security-questions');
        Route::post('/users/security-questions', [UserController::class, 'updateSecurityQuestions'])->name('users.update-security-questions');

        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::post('/products/update', [ProductController::class, 'update'])->name('products.update');
        Route::post('/products/destroy', [ProductController::class, 'destroy'])->name('products.destroy');

        Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::get('/dashboard/products-amount/{year}', [DashboardController::class, 'getProductsAmount'])->name('dashboard.products-amount');
        Route::get('/dashboard/category-sold/{year}', [DashboardController::class, 'getCategorySold'])->name('dashboard.category-sold');
        Route::get('/dashboard/products-amount-by-year/{year}', [DashboardController::class, 'getProductsAmountByYear'])->name('dashboard.products-amount-by-year');
        Route::get('/dashboard/amount-by-month/{year}', [DashboardController::class, 'getAmountByMonth'])->name('dashboard.amount-by-month');
        Route::get('/dashboard/export-excel/{year}', [DashboardController::class, 'exportExcel'])->name('dashboard.export-excel');
    });
    Route::post('/admin/orders/update-status', [AdminOrderController::class, 'update'])->name('admin.orders.update');
    Route::post('/admin/orders/create', [AdminOrderController::class, 'create'])->name('admin.orders.create');
    
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/customer/products', [CustomerProductController::class, 'index'])->name('customer.products.index');
    Route::get('/customer/products/{id}', [CustomerProductController::class, 'show'])->name('customer.products.show');
    Route::post('/customer/products/add-to-cart', [CustomerProductController::class, 'addToCart'])->name('customer.products.add-to-cart');
    Route::post('/customer/products/update', [CustomerProductController::class, 'updateQty'])->name('customer.products.update');
    Route::post('/customer/products/remove-from-cart', [CustomerProductController::class, 'destroy'])->name('customer.products.remove-from-cart');
    Route::post('/customer/products/checkout', [CheckoutController::class, 'checkout'])->name('customer.products.checkout');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

    Route::get('/customer/orders', [CustomerOrderController::class, 'index'])->name('customer.orders.index');

    Route::get('/customer/orders/{orderId}/pdf', [PdfController::class, 'orderPdf'])->name('customer.orders.pdf');
});

