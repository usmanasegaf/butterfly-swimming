<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'swimming_course_id',
        'day_of_week',
        'start_time',
        'end_time',
        'location',
        'instructor',
        'is_active'
    ];

    /**
     * Get the swimming course that owns the schedule.
     */
    public function swimmingCourse()
    {
        return $this->belongsTo(SwimmingCourse::class);
    }
}
