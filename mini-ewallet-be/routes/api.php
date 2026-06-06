<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use Illuminate\Routing\Middleware\ThrottleRequests;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Resources\UserResource;

Route::post("user/login", [UserController::class, "auth"])->middleware(
    ThrottleRequests::class . ":5,1",
);

Route::middleware("auth:sanctum")->group(function () {
    Route::get("user", function (Request $request) {
        return UserResource::make($request->user());
    });
    Route::get("user/dashboard", [WalletController::class, "show"]);
    Route::post("user/logout", [UserController::class, "logout"]);
    Route::post("user/send", [TransactionController::class, "transfer"]);
    Route::get("user/transactions", [TransactionController::class, "index"]);
});
