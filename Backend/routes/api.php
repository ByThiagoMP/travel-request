<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TravelOrderController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/notifications', [NotificationController::class, 'index']);

    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead']);
    });

    Route::prefix('pedidos')->group(function () {
        Route::get('/', [TravelOrderController::class, 'index']);
        Route::post('/', [TravelOrderController::class, 'store']);
        Route::put('/{id}', [TravelOrderController::class, 'update']);
        Route::get('/travel-order-status', [TravelOrderController::class, 'traverOrderStatus']);
        Route::get('/{id}', [TravelOrderController::class, 'show']);
        Route::patch('/{id}/status', [TravelOrderController::class, 'updateStatus']);
    });

    Route::prefix('users')->group(function () {
        Route::put('{id}/change-permission', [AuthController::class, 'changePermission']);
    });
});