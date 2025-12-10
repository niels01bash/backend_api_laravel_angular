<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
//use Illuminate\Routing\Router;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/v1/auth')->group(function(){

    Route::post("/login", [AuthController::class, "funLogin"]);
    Route::post("/register", [AuthController::class, "funRegister"]);

        Route::middleware('auth:sanctum')->group(function(){

            Route::get("/profile", [AuthController::class, "funProfile"]);
            Route::post("/logout", [AuthController::class, "funLogout"]);

        });
});


//CRUD usuarios
  Route::apiResource("usuario", UsuarioController ::class);
