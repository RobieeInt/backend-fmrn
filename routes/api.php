<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\JhajanController;
use App\Http\Controllers\API\MidtransController;
use App\Http\Controllers\API\TransactionsController;
use App\Http\Controllers\API\FoodController;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [UserController::class, 'fetch']);
    Route::post('user', [UserController::class, 'updateProfile']);
    Route::post('user/photo', [UserController::class, 'updatePhoto']);

    Route::post('logout', [UserController::class, 'logout']);

    Route::post('checkout', [TransactionsController::class, 'checkout']);
    
    Route::get('transactions', [TransactionsController::class, 'all']);
    Route::post('transactions/{id}', [TransactionsController::class, 'update']);

});


Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);


Route::get('jhajan', [JhajanController::class, 'all']);
Route::get('food', [FoodController::class, 'all']);

Route::post('midtrans/callback', [MidtransController::class, 'callback']);