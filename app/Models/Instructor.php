<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'bio',
        'specialization',
        'certification',
        'image',
        'is_active'
    ];

    /**
     * Get the swimming courses for the instructor.
     */
    public function swimmingCourses()
    {
        return $this->hasMany(SwimmingCourse::class);
    }
}
    