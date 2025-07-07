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
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'attended_at' => 'datetime',
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