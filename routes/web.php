<?php

use Illuminate\Http\Request; // Tambahkan ini jika belum ada
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\SwimmingCourseManagementController; // Diubah!
use App\Http\Controllers\Admin\RegistrationManagementController; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route untuk halaman utama
Route::get('/', [HomeController::class, 'index'])->name('home');

// Route untuk autentikasi
Route::controller(AuthController::class)->group(function () {
    Route::get('register', 'register')->name('register');
    Route::post('register', 'registerSave')->name('register.save');

    Route::get('login', 'login')->name('login');
    Route::post('login', 'loginAction')->name('login.action');

    // Ubah dari Route::get menjadi Route::post untuk logout
    Route::post('logout', 'logout')->name('logout');
});

// Route yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::controller(AuthController::class)->group(function () {
        Route::get('profile', 'profile')->name('profile');
        // Pastikan ini juga POST/PUT sesuai penggunaan form di profile.blade.php
        Route::post('profile', 'profileUpdate')->name('profile.update'); // Biasanya PUT/PATCH, tapi jika form di blade pakai POST, ini harus POST juga
    });

    // Route untuk kursus renang (hanya admin yang bisa CRUD)
    Route::middleware('role:admin')->group(function () {
        Route::resource('swimming-courses', SwimmingCourseController::class);
        Route::resource('registrations', RegistrationController::class)->except(['create', 'store']);
        Route::put('registrations/{registration}/status', [RegistrationController::class, 'updateStatus'])->name('registrations.update-status');
    });

    // Route untuk pendaftaran kursus (semua user)
    Route::get('my-registrations', [RegistrationController::class, 'myRegistrations'])->name('my-registrations');
    Route::get('register-course', [RegistrationController::class, 'create'])->name('register-course');
    Route::post('register-course', [RegistrationController::class, 'store'])->name('register-course.store');

        // --- Rute Khusus Admin ---
    // Tambahkan blok ini untuk rute admin
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        // Rute untuk Manajemen Kursus Renang (CRUD penuh)
        Route::resource('swimming-course-management', SwimmingCourseManagementController::class);

        // Rute untuk Manajemen Pendaftaran
        Route::resource('registration-management', RegistrationManagementController::class)->except(['create', 'store', 'edit']);
    });
    // --- Akhir dari Rute Admin ---
});