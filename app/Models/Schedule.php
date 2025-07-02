<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'murid_id', 'date', 'start_time', 'end_time', 'location',
    ];

    protected $dates = ['date', 'start_time', 'end_time'];

    public function murid()
    {
        return $this->belongsTo(Murid::class);
    }

    public function murids()
    {
        return $this->belongsToMany(Murid::class, 'schedule_murid', 'schedule_id', 'murid_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    public function swimmingCourse()
    {
        return $this->belongsTo(SwimmingCourse::class, 'swimming_course_id');
    }
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
}
