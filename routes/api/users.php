<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix("/users")
    ->middleware("auth:api")
    ->group(function () {
        Route::get("/{userId}", [UserController::class, "findOne"]);

        Route::get("/", [UserController::class, "findAll"]);

        Route::post("/", [UserController::class, "create"]);

        Route::put("/{userId}", [UserController::class, "update"]);

        Route::delete("/{userId}", [UserController::class, "delete"]);
});
