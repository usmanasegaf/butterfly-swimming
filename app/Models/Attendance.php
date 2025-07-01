<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id', 'murid_id', 'guru_id', 'location_id', 'date', 'is_present'
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function murid()
    {
        return $this->belongsTo(Murid::class);
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}