<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'date', 'start_time', 'end_time', 'location'
    ];

    protected $dates = ['date', 'start_time', 'end_time'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}