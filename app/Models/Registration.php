<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'swimming_course_id',
        'start_date',
        'end_date',
        'status',
        'payment_status',
        'notes'
    ];

    /**
     * Get the user that owns the registration.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the swimming course that owns the registration.
     */
    public function swimmingCourse()
    {
        return $this->belongsTo(SwimmingCourse::class);
    }
}
