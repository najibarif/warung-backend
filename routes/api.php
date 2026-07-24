<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\PriceHistoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — AplikasiWarung
|--------------------------------------------------------------------------
*/

// ── Public routes ────────────────────────────────────────────────────────
Route::get('/ping', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
    ]);
});

Route::post('/login', [AuthController::class, 'login']);

// Products (public read)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::get('/products/barcode/{barcode}', [ProductController::class, 'findByBarcode']);

// Categories (public read)
Route::get('/categories', [CategoryController::class, 'index']);

// ── Authenticated routes ─────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Favorites (any authenticated user)
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites', [FavoriteController::class, 'store']);
    Route::delete('/favorites/{productId}', [FavoriteController::class, 'destroy']);

    // Price history (auth)
    Route::get('/products/{product}/price-history', [PriceHistoryController::class, 'index']);

    // ── Admin-only routes ────────────────────────────────────────────────
    Route::middleware('admin')->group(function () {

        // Products CRUD
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);

        // Categories CRUD
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{category}', [CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
        // Kasir & Dashboard
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);
        Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    });

    // Orders checkout (any logged in customer/admin)
    Route::post('/orders', [OrderController::class, 'store']);
});
