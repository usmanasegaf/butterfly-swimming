<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\SwimmingCourseManagementController;
use App\Http\Controllers\Admin\RegistrationManagementController;
use App\Http\Controllers\User\CourseController;
use App\Http\Controllers\User\RegistrationController;

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

    Route::post('logout', 'logout')->name('logout');
});

// Route yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::controller(AuthController::class)->group(function () {
        Route::get('profile', 'profile')->name('profile');
        Route::post('profile', 'profileUpdate')->name('profile.update');
    });

    // Route untuk pendaftaran kursus (semua user)
    Route::middleware('permission:view own registrations')->group(function() {
        // Replace myRegistrations with index since that's the method name in your controller
        Route::get('my-registrations', [RegistrationController::class, 'index'])->name('my-registrations');
    });
    
    Route::middleware('permission:register to course')->group(function() {
        Route::get('register-course', [RegistrationController::class, 'create'])->name('register-course');
        Route::post('register-course', [RegistrationController::class, 'store'])->name('register-course.store');
    });

    // --- User Routes for swimming courses ---
    Route::name('user.')->prefix('user')->group(function () {
        // Course routes
        Route::resource('courses', CourseController::class)->only(['index', 'show']);
        
        // Registration routes
        Route::resource('registrations', RegistrationController::class)->only(['index', 'create', 'store', 'show']);
        Route::patch('registrations/{registration}/cancel', [RegistrationController::class, 'cancel'])->name('registrations.cancel');
    });

    // --- Rute Khusus Admin ---
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::resource('swimming-course-management', SwimmingCourseManagementController::class);

        // Rute untuk Manajemen Pendaftaran
        // Permission checks dilakukan di dalam controller
        Route::resource('registration-management', RegistrationManagementController::class)->except(['create', 'store', 'edit']);
    });
    // --- Akhir dari Rute Admin ---
});