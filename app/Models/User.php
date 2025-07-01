<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

// Added this trait

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles; // Added HasRoles trait

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // We'll keep this for backward compatibility
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    /**
     * Get the registrations for the user.
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Get the swimming courses for the user through registrations.
     */
    public function swimmingCourses()
    {
        return $this->hasManyThrough(
            SwimmingCourse::class,
            Registration::class,
            'user_id',
            'id',
            'id',
            'swimming_course_id'
        );
    }
// Untuk guru: dapatkan semua murid bimbingan
    public function murids()
    {
        return $this->belongsToMany(User::class, 'guru_murid', 'guru_id', 'murid_id');
    }

// Untuk murid: dapatkan semua guru pembimbing
    public function gurus()
    {
        return $this->belongsToMany(User::class, 'guru_murid', 'murid_id', 'guru_id');
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'guru_id');
    }
}
