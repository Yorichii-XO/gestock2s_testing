<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UsersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/login',[ApiController::class,'login'] );
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [RegisterController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/save-message', [MessageController::class, 'saveMessage']);
Route::get('/messages', [MessageController::class, 'showMessages']);
// Route::get('/categories',[CategoryController::class,'index'] );
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::put('/products/{id}', [ProductController::class, 'update']);

    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('suppliers', SupplierController::class);
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('orders', OrderController::class);
    Route::apiResource('inventories', InventoryController::class);
    Route::apiResource('order-items', OrderItemController::class);
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('users', UsersController::class);

    Route::post('logout', [ApiController::class, 'logout']);
});
