<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::prefix("/auth")->group(function () {
    Route::post("/login", [AuthController::class, "login"]);

    Route::get("/me", [AuthController::class, "me"])
        ->middleware("auth:api");
});
