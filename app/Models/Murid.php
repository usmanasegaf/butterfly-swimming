<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Murid extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'expired_at',
    ];

    protected $dates = ['expired_at'];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
