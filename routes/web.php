<?php

use App\Http\Controllers\Admin\SwimmingCourseManagementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Guru\GuruAttendanceController;
use App\Http\Controllers\Guru\GuruCourseController;
use App\Http\Controllers\Murid\MuridAttendanceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\User\CourseController;
use App\Http\Controllers\User\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::controller(AuthController::class)->group(function () {
    Route::get('register', 'register')->name('register');
    Route::post('register', 'registerSave')->name('register.save');

    Route::get('login', 'login')->name('login');
    Route::post('login', 'loginAction')->name('login.action');

    Route::post('logout', 'logout')->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::controller(AuthController::class)->group(function () {
        Route::get('profile', 'profile')->name('profile');
        Route::post('profile', 'profileUpdate')->name('profile.update');
    });

    Route::middleware('permission:view own registrations')->group(function () {
        Route::get('my-registrations', [RegistrationController::class, 'index'])->name('my-registrations');
    });

    Route::middleware('permission:register to course')->group(function () {
        Route::get('register-course', [RegistrationController::class, 'create'])->name('register-course');
        Route::post('register-course', [RegistrationController::class, 'store'])->name('register-course.store');
    });

    Route::name('user.')->prefix('user')->group(function () {
        Route::resource('courses', CourseController::class)->only(['index', 'show']);
        Route::resource('registrations', RegistrationController::class)->only(['index', 'create', 'store', 'show']);
        Route::patch('registrations/{registration}/cancel', [RegistrationController::class, 'cancel'])->name('registrations.cancel');
    });

    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::resource('swimming-course-management', SwimmingCourseManagementController::class);
    });

    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/admin/guru-pending', [App\Http\Controllers\Admin\GuruVerificationController::class, 'index'])->name('admin.guru.pending');
        Route::post('/admin/guru-verifikasi/{user}', [App\Http\Controllers\Admin\GuruVerificationController::class, 'verify'])->name('admin.guru.verify');
        Route::post('/admin/guru-tolak/{user}', [App\Http\Controllers\Admin\GuruVerificationController::class, 'reject'])->name('admin.guru.reject');
        Route::get('/admin/guru-list', [App\Http\Controllers\Admin\GuruListController::class, 'index'])->name('admin.guru.list');
    });

    Route::get('/admin/dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('admin.dashboard')->middleware(['auth', 'role:admin']);

    Route::get('/guru/dashboard', [App\Http\Controllers\Guru\GuruDashboardController::class, 'index'])->name('guru.dashboard')->middleware(['auth', 'role:guru']);

    Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
        Route::get('attendance', [App\Http\Controllers\Guru\GuruAttendanceController::class, 'index'])->name('attendance.index');
        Route::get('attendance/create/{schedule_id}', [App\Http\Controllers\Guru\GuruAttendanceController::class, 'create'])->name('attendance.create');
        Route::post('attendance/store', [App\Http\Controllers\Guru\GuruAttendanceController::class, 'store'])->name('attendance.store');

        // --- START: Rute Baru untuk Laporan Absensi ---
        Route::get('attendances/report', [GuruAttendanceController::class, 'generateReport'])->name('attendances.report.generate');
        // --- END: Rute Baru untuk Laporan Absensi ---

        Route::get('murid', [App\Http\Controllers\Guru\GuruMuridController::class, 'index'])->name('murid.index');
        Route::get('murid/create', [App\Http\Controllers\Guru\GuruMuridController::class, 'create'])->name('murid.create');
        Route::post('murid/store', [App\Http\Controllers\Guru\GuruMuridController::class, 'store'])->name('murid.store');
        Route::delete('murid/{id}', [App\Http\Controllers\Guru\GuruMuridController::class, 'destroy'])->name('murid.destroy');

        Route::get('/courses', [GuruCourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/{swimmingCourse}/create-schedule', [GuruCourseController::class, 'createScheduleForm'])->name('courses.create_schedule_form');
        Route::post('/schedules', [GuruCourseController::class, 'storeSchedule'])->name('schedules.store');
        Route::get('/schedules/{schedule}/edit', [GuruCourseController::class, 'editSchedule'])->name('schedules.edit');
        Route::put('/schedules/{schedule}', [GuruCourseController::class, 'updateSchedule'])->name('schedules.update');
        Route::delete('/schedules/{schedule}', [GuruCourseController::class, 'destroySchedule'])->name('schedules.destroy');

        Route::get('schedules/{schedule}/attendance', [GuruAttendanceController::class, 'showAttendanceForm'])->name('schedules.show_attendance_form');
        Route::post('schedules/{schedule}/attendance', [GuruAttendanceController::class, 'storeAttendance'])->name('schedules.store_attendance');

        Route::get('attendance', [GuruAttendanceController::class, 'index'])->name('attendance.index');

        Route::get('murid/{murid}/assign-course', [App\Http\Controllers\Guru\GuruMuridController::class, 'assignCourseForm'])->name('murid.assign_course_form');
        Route::post('murid/{murid}/assign-course', [App\Http\Controllers\Guru\GuruMuridController::class, 'assignCourse'])->name('murid.assign_course');

    });

    Route::get('/murid/dashboard', [App\Http\Controllers\Murid\MuridDashboardController::class, 'index'])->name('murid.dashboard')->middleware(['auth', 'role:murid']);
    Route::get('/murid', [App\Http\Controllers\Murid\MuridController::class, 'index'])->name('murid.index')->middleware(['auth', 'role:murid']);

    // --- START: Rute Baru untuk Murid Attendance ---
    Route::middleware(['auth', 'role:murid'])->prefix('murid')->name('murid.')->group(function () {
        Route::get('attendance-history', [MuridAttendanceController::class, 'index'])->name('attendance.index');
        Route::get('attendances/report', [MuridAttendanceController::class, 'generateReport'])->name('attendances.report.generate');
    });
    // --- END: Rute Baru untuk Murid Attendance ---

    Route::prefix('adminNGuru')->middleware('role:admin|guru')->group(function () {
        Route::get('/guru/murid-pending', [App\Http\Controllers\Guru\MuridVerificationController::class, 'index'])->name('guru.murid.pending');
        Route::post('/guru/murid-verifikasi/{user}', [App\Http\Controllers\Guru\MuridVerificationController::class, 'verify'])->name('guru.murid.verify');
        Route::post('/guru/murid-tolak/{user}', [App\Http\Controllers\Guru\MuridVerificationController::class, 'reject'])->name('guru.murid.reject');
    });

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
});
