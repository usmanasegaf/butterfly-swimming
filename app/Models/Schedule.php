<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; 

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'swimming_course_id',
        'guru_id',
        'location_id',
        'day_of_week',
        'start_time_of_day',
        'end_time_of_day',
        'max_students',
        'status',
        'murid_id',
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


    // Relasi untuk absensi yang terkait dengan jadwal ini
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function murid(): BelongsTo
    {
        return $this->belongsTo(User::class, 'murid_id');
    }
}