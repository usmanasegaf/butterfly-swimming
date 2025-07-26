<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwimmingCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level',
        'description',
        'price',
        'duration',
        'is_active',
        'jumlah_pertemuan',
    ];

    /**
     * Get the registrations for the swimming course.
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Get the schedules for the swimming course.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Get the users for the swimming course through registrations.
     */
    public function users()
    {
        return $this->hasManyThrough(
            User::class,
            Registration::class,
            'swimming_course_id',
            'id',
            'id',
            'user_id'
        );
    }
}
