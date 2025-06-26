<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Murid extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'expired_at'
    ];

    protected $dates = ['expired_at'];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}