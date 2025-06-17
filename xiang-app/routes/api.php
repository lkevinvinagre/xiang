<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::group(['middleware'=>'api','prefix'=>'account'],function(){
    Route::get('list',[AccountController::class,'list']);
    Route::post('add',[AccountController::class,'add']);
    Route::get('show',[AccountController::class,'show']);
    Route::patch('edit',[AccountController::class,'edit']);
    Route::delete('destroy',[AccountController::class,'destroy']);
    Route::post('decrypt',[AccountController::class,'decrypt']);
});
Route::group(['middleware'=>'api','prefix'=>'auth'],function () {
    Route::post('login',[AuthController::class,'login']);
    Route::get('logout',[AuthController::class,'logout']);
    Route::post('register',[AuthController::class,'register']);
    Route::get('profile',[AuthController::class,'profile']);
});
