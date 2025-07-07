<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    // ... (properti fillable atau guarded Anda yang sudah ada)
    protected $fillable = [
        'swimming_course_id',
        'guru_id',
        'location_id',
        'day_of_week',
        'start_time_of_day',
        'end_time_of_day',
        'max_students',
        'status',
    ];

    public function swimmingCourse()
    {
        return $this->belongsTo(SwimmingCourse::class);
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id'); // Asumsi guru adalah User
    }

    // Pastikan relasi ini sudah ada, jika belum, tambahkan
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // Relasi untuk murid yang terdaftar pada jadwal ini
    // ASUMSI: Murid terdaftar ke jadwal melalui tabel pivot 'schedule_user'
    // Jika struktur database Anda berbeda, relasi ini perlu disesuaikan.
    public function students()
    {
        // Ubah 'schedule_user' jika nama tabel pivot Anda berbeda
        // Ubah 'user_id' jika nama kolom foreign key untuk user di pivot berbeda
        return $this->belongsToMany(User::class, 'schedule_user', 'schedule_id', 'user_id')
                    ->where('role', 'murid'); // Hanya ambil user dengan role 'murid'
    }

    // Relasi untuk absensi yang terkait dengan jadwal ini
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

        public function murids()
    {
        return $this->belongsToMany(User::class, 'schedule_murid', 'schedule_id', 'murid_id');
    }
}