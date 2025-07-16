<?php

use App\Http\Controllers\Admin\AdminAttendanceController;
use App\Http\Controllers\Admin\AdminMuridController;
use App\Http\Controllers\Admin\AdminScheduleController;
use App\Http\Controllers\Admin\SwimmingCourseManagementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Guru\GuruAttendanceController;
use App\Http\Controllers\Guru\GuruCourseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Murid\MuridAttendanceController;
use App\Http\Controllers\NotificationController;
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

    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

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

    // --- GRUP ADMIN UTAMA (TANPA ->name('admin.') yang dirantai di sini) ---
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        // Rute swimming-course-management tetap tanpa awalan 'admin.' di namanya
        Route::resource('swimming-course-management', SwimmingCourseManagementController::class);

        // Rute jadwal, absensi, dan murid akan diberi nama dengan awalan 'admin.' secara eksplisit
        Route::resource('schedules', AdminScheduleController::class)->names([
            'index'   => 'admin.schedules.index',
            'create'  => 'admin.schedules.create',
            'store'   => 'admin.schedules.store',
            'show'    => 'admin.schedules.show',
            'edit'    => 'admin.schedules.edit',
            'update'  => 'admin.schedules.update',
            'destroy' => 'admin.schedules.destroy',
        ]);

        Route::get('attendances', [AdminAttendanceController::class, 'index'])->name('admin.attendances.index');
        Route::get('attendances/report', [AdminAttendanceController::class, 'generateReport'])->name('admin.attendances.report.generate');

        Route::get('murids', [AdminMuridController::class, 'index'])->name('admin.murids.index');
        Route::get('murids/{murid}/edit', [AdminMuridController::class, 'edit'])->name('admin.murids.edit');
        Route::put('murids/{murid}', [AdminMuridController::class, 'update'])->name('admin.murids.update');
        Route::delete('murids/{murid}', [AdminMuridController::class, 'destroy'])->name('admin.murids.destroy');

        // Rute verifikasi guru dan daftar guru (sudah benar dengan admin. prefix)
        Route::get('guru-pending', [App\Http\Controllers\Admin\GuruVerificationController::class, 'index'])->name('admin.guru.pending');
        Route::post('guru-verifikasi/{user}', [App\Http\Controllers\Admin\GuruVerificationController::class, 'verify'])->name('admin.guru.verify');
        Route::post('guru-tolak/{user}', [App\Http\Controllers\Admin\GuruVerificationController::class, 'reject'])->name('admin.guru.reject');
        Route::get('guru-list', [App\Http\Controllers\Admin\GuruListController::class, 'index'])->name('admin.guru.list');

        // Rute dashboard admin (sudah benar dengan admin. prefix)
        Route::get('dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('admin.dashboard');
    });

    // --- Rute Guru ---
    Route::get('/guru/dashboard', [App\Http\Controllers\Guru\GuruDashboardController::class, 'index'])->name('guru.dashboard')->middleware(['auth', 'role:guru']);

    Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
        Route::get('attendance', [App\Http\Controllers\Guru\GuruAttendanceController::class, 'index'])->name('attendance.index');
        Route::get('attendance/create/{schedule_id}', [App\Http\Controllers\Guru\GuruAttendanceController::class, 'create'])->name('attendance.create');
        Route::post('attendance/store', [App\Http\Controllers\Guru\GuruAttendanceController::class, 'store'])->name('attendance.store');

        Route::get('attendances/report', [GuruAttendanceController::class, 'generateReport'])->name('attendances.report.generate');

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

        // Hapus rute lama untuk assign-course-form yang merender halaman terpisah
        // Route::get('murid/{murid}/assign-course', [App\Http\Controllers\Guru\GuruMuridController::class, 'assignCourseForm'])->name('murid.assign_course_form');

        // Rute untuk menugaskan/mengubah kursus melalui modal (POST)
        Route::post('murid/{murid}/assign-course', [App\Http\Controllers\Guru\GuruMuridController::class, 'assignCourse'])->name('murid.assign_course');
        // Rute BARU untuk memperpanjang kursus melalui modal (POST)
        Route::post('murid/{murid}/extend-course', [App\Http\Controllers\Guru\GuruMuridController::class, 'extendCourse'])->name('murid.extend_course');
    });

    // --- Rute Murid ---
    Route::get('/murid/dashboard', [App\Http\Controllers\Murid\MuridDashboardController::class, 'index'])->name('murid.dashboard')->middleware(['auth', 'role:murid']);
    Route::get('/murid', [App\Http\Controllers\Murid\MuridController::class, 'index'])->name('murid.index')->middleware(['auth', 'role:murid']);

    Route::middleware(['auth', 'role:murid'])->prefix('murid')->name('murid.')->group(function () {
        Route::get('attendance-history', [MuridAttendanceController::class, 'index'])->name('attendance.index');
        Route::get('attendances/report', [MuridAttendanceController::class, 'generateReport'])->name('attendances.report.generate');
    });

    Route::prefix('adminNGuru')->middleware('role:admin|guru')->group(function () {
        Route::get('/guru/murid-pending', [App\Http\Controllers\Guru\MuridVerificationController::class, 'index'])->name('guru.murid.pending');
        Route::post('/guru/murid-verifikasi/{user}', [App\Http\Controllers\Guru\MuridVerificationController::class, 'verify'])->name('guru.murid.verify');
        Route::post('/guru/murid-tolak/{user}', [App\Http\Controllers\Guru\MuridVerificationController::class, 'reject'])->name('guru.murid.reject');

        Route::delete('/guru/murid-delete/{user}', [App\Http\Controllers\Guru\MuridVerificationController::class, 'delete'])->name('guru.murid.delete');

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
