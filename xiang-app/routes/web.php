<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/accounts', function () {
    return view('layouts.accounts');
});