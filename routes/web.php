<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index']);
Route::get('/program', [HomeController::class, 'program']);
Route::get('/program/{id}', [HomeController::class, 'programDetail']);
Route::get('/jadwal', [HomeController::class, 'jadwal']);
Route::get('/pelatih', [HomeController::class, 'pelatih']);
Route::get('/tentang', [HomeController::class, 'tentang']);
Route::get('/kontak', [HomeController::class, 'kontak']);
Route::get('/daftar', [HomeController::class, 'daftar']);