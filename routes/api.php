<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OperationsController;
use App\Http\Controllers\Api\HistoryBalanceController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\HistoryMoneyController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => ['auth:sanctum', 'isAdmin']], function () {
    Route::apiResources([
        'users' => UserController::class,
        'service' => ServiceController::class,
    ]);

});

Route::get('/service', [ServiceController::class, 'index'])->middleware('auth:sanctum');

Route::get('/historyBalance', [HistoryBalanceController::class, 'index'])->middleware('auth:sanctum');
Route::get('/historyBalance/{id}', [HistoryBalanceController::class, 'show'])->middleware(['auth:sanctum', 'isAdmin']);
Route::delete('/historyBalance/{id}', [HistoryBalanceController::class, 'destroy'])->middleware(['auth:sanctum', 'isAdmin']);
Route::put('/historyBalance/{id}', [HistoryBalanceController::class, 'update'])->middleware(['auth:sanctum', 'isAdmin']);

Route::put('/amountOperations', [OperationsController::class, 'amountOperations'])->middleware('auth:sanctum');
Route::put('/moneyOperations', [OperationsController::class, 'moneyOperations'])->middleware('auth:sanctum');

Route::get('/historyMoney/{id}', [HistoryMoneyController::class, 'show'])->middleware(['auth:sanctum', 'isAdmin']);
Route::get('/historyMoney', [HistoryMoneyController::class, 'index'])->middleware('auth:sanctum');
Route::delete('/historyMoney/{id}', [HistoryMoneyController::class, 'destroy'])->middleware(['auth:sanctum', 'isAdmin']);

Route::get('/deneme/{id}', [UserController::class, 'showInfo'])->middleware(['auth:sanctum', 'isAdmin']);

Route::post('/login', [UserController::class, 'login']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users', [UserController::class, 'index'])->middleware('auth:sanctum');
Route::get('/user/allUsers', [UserController::class, 'allUsers']);
