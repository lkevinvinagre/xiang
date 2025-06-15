<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::apiResource('accounts',AccountController::class);
Route::group(['middleware'=>'api','prefix'=>'auth'],function () {
    Route::post('login',[AuthController::class,'login']);
    Route::get('logout',[AuthController::class,'logout']);
    Route::post('register',[AuthController::class,'register']);
    Route::get('profile',[AuthController::class,'profile']);
});
