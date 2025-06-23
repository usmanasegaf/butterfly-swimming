<?php

use Illuminate\Support\Facades\Route;

// Contoh route API sederhana
Route::get('/test', function () {
    return response()->json(['message' => 'API works!']);
});

// Route untuk controller API (akan dibuat di langkah 4)
Route::get('/users', 'App\Http\Controllers\Api\UserController@index');