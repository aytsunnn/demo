<?php

use App\Http\Controllers\ProductController;
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
    return redirect()->route('login');
});

Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::get('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Auth::routes();

Route::get('/products', "App\\Http\\Controllers\\ProductController@index")->name('products');
Route::get('/products/create', "App\\Http\\Controllers\\ProductController@create")->name('products.create');
Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::post('/products', "App\\Http\\Controllers\\ProductController@store")->name('products.store');
Route::put('/products/{id}', "App\\Http\\Controllers\\ProductController@update")->name('products.update');
Route::delete('/products/{id}', "App\\Http\\Controllers\\ProductController@destroy")->name('products.destroy');

Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders');
Route::get('/orders/create', [\App\Http\Controllers\OrderController::class, 'create'])->name('orders.create');
Route::get('/orders/{id}/edit', [\App\Http\Controllers\OrderController::class, 'edit'])->name('orders.edit');
Route::post('/orders', [\App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
Route::put('/orders/{id}', [\App\Http\Controllers\OrderController::class, 'update'])->name('orders.update');
Route::delete('orders/{id}', [\App\Http\Controllers\OrderController::class, 'destroy'])->name('orders.destroy');
