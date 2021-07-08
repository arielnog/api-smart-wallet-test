<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Wallet\WalletController;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('create-user', [UserController::class, 'createUser']);
});

Route::group(['middleware' => ['jwt']], function () {
    Route::prefix('wallet')->group(function () {
        Route::post('do-deposit', [WalletController::class, 'deposit']);
        Route::post('do-transfer', [WalletController::class, 'transfer']);
    });
});

