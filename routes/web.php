<?php

use App\Http\Controllers\Admin\RegistrationManagementController;
use App\Http\Controllers\Admin\SwimmingCourseManagementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\User\CourseController;
use App\Http\Controllers\User\RegistrationController;
use Illuminate\Support\Facades\Route;

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
    Route::middleware('permission:view own registrations')->group(function () {
        // Replace myRegistrations with index since that's the method name in your controller
        Route::get('my-registrations', [RegistrationController::class, 'index'])->name('my-registrations');
    });

    Route::middleware('permission:register to course')->group(function () {
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

    // --- Rute verifikasi ---
    // verifikasi guru oleh admin
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/admin/guru-pending', [App\Http\Controllers\Admin\GuruVerificationController::class, 'index'])->name('admin.guru.pending');
        Route::post('/admin/guru-verifikasi/{user}', [App\Http\Controllers\Admin\GuruVerificationController::class, 'verify'])->name('admin.guru.verify');
        Route::post('/admin/guru-tolak/{user}', [App\Http\Controllers\Admin\GuruVerificationController::class, 'reject'])->name('admin.guru.reject');
        Route::get('/admin/guru-list', [App\Http\Controllers\Admin\GuruListController::class, 'index'])->name('admin.guru.list');
    });

    // verifikasi murid oleh guru
    Route::middleware(['auth', 'role:guru'])->group(function () {
        Route::get('/guru/murid-pending', [App\Http\Controllers\Guru\MuridVerificationController::class, 'index'])->name('guru.murid.pending');
        Route::post('/guru/murid-verifikasi/{user}', [App\Http\Controllers\Guru\MuridVerificationController::class, 'verify'])->name('guru.murid.verify');
        Route::post('/guru/murid-tolak/{user}', [App\Http\Controllers\Guru\MuridVerificationController::class, 'reject'])->name('guru.murid.reject');
    });

    // Rute akun belum terverifikasi
    Route::get('/belum-verifikasi', function () {
        return view('auth.belum_verifikasi');
    })->name('belum.verifikasi');

    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'guru') {
            return redirect()->route('guru.dashboard');
        } elseif ($user->role === 'murid') {
            return redirect()->route('murid.dashboard');
        }
        abort(403);
    })->middleware('auth')->name('dashboard');

    Route::get('/admin/dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('admin.dashboard')->middleware(['auth', 'role:admin']);

    Route::get('/guru/dashboard', [App\Http\Controllers\Guru\GuruDashboardController::class, 'index'])->name('guru.dashboard')->middleware(['auth', 'role:guru']);

    Route::get('/murid/dashboard', [App\Http\Controllers\Murid\MuridDashboardController::class, 'index'])->name('murid.dashboard')->middleware(['auth', 'role:murid']);
});
// checkpoint
