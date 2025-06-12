<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/cek-email', [AuthController::class, 'cekEmail']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::put('/user/update', [AuthController::class, 'update']);
    Route::put('/user/update-password', [AuthController::class, 'ubahPassword']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Product routes
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::patch('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    //order routes
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index']);

    // Cart routes
    Route::get('/cart', [CartController::class, 'index']);              // Lihat isi keranjang
    Route::post('/cart/items', [CartController::class, 'addItem']);     // Tambah produk ke keranjang
    Route::put('/cart/items/{itemId}', [CartController::class, 'updateItem']);  // Update jumlah produk
    Route::delete('/cart/items/{itemId}', [CartController::class, 'removeItem']); // Hapus produk dari keranjang
});
