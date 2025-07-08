<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'student_id',
        'attendance_date',
        'status', // e.g., 'hadir', 'alpha'
        'attended_at', // Timestamp when attendance was taken
        'teacher_latitude',    // Tambahkan kolom baru
        'teacher_longitude',   // Tambahkan kolom baru
        'distance_from_course',// Tambahkan kolom baru
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'attended_at' => 'datetime',
        'teacher_latitude' => 'float',  // Opsional: cast ke float/double
        'teacher_longitude' => 'float', // Opsional: cast ke float/double
        'distance_from_course' => 'float', // Opsional: cast ke float/double
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id'); // Asumsi student adalah User
    }
}