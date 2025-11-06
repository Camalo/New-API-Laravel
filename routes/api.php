<?php

use App\Infrastructure\Http\BalanceController;
use App\Infrastructure\Http\DepositController;
use App\Infrastructure\Http\TransferController;
use App\Infrastructure\Http\WithdrawController;
use Illuminate\Support\Facades\Route;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post(
    '/deposit',
    DepositController::class
);
Route::post(
    '/withdraw',
    WithdrawController::class
);
Route::post(
    '/transfer',
    TransferController::class
);
Route::get(
    '/balance/{user_id}',
    BalanceController::class
);
