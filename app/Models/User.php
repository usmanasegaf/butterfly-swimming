<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles; // Asumsi Anda menggunakan Spatie Roles & Permissions

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Pertahankan jika digunakan untuk identifikasi peran
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function swimmingCourses()
    {
        return $this->hasManyThrough(
            SwimmingCourse::class,
            Registration::class,
            'user_id',
            'id',
            'id',
            'swimming_course_id'
        );
    }

    // Untuk guru: dapatkan semua murid bimbingan
    // Ini adalah relasi yang akan kita gunakan di Controller
    public function murids()
    {
        return $this->belongsToMany(User::class, 'guru_murid', 'guru_id', 'murid_id');
    }

    // Untuk murid: dapatkan semua guru pembimbing
    // Ini sudah ada dan tidak duplikat dengan `bimbinganGurus`
    public function gurus()
    {
        return $this->belongsToMany(User::class, 'guru_murid', 'murid_id', 'guru_id');
    }

    public function attendances()
    {
        // Pastikan ini benar, apakah 'guru_id' di tabel attendances merujuk ke user_id dari guru
        return $this->hasMany(Attendance::class, 'guru_id');
    }

    public function schedules()
    {
        // Relasi jika user ini (sebagai murid) terhubung ke jadwal
        return $this->belongsToMany(Schedule::class, 'schedule_murid', 'murid_id', 'schedule_id');
    }

    public function jadwalGuru()
    {
        // Relasi jika user ini (sebagai guru) mengampu jadwal
        return $this->hasMany(Schedule::class, 'guru_id');
    }
}