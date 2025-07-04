<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'swimming_course_id',
        'guru_id',
        'location_id',
        'day_of_week',       // Tambahkan ini
        'start_time_of_day', // Tambahkan ini
        'end_time_of_day',   // Tambahkan ini
        'max_students',
        'status',
        // Tambahkan kolom lain jika ada yang relevan untuk diisi secara massal
    ];

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
