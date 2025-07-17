<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_verified',
        'google_id',
        'guru_id',
        'phone_number',
        'address',
        'birthdate',
        'profile_picture',
        'swimming_course_id', // Ditambahkan untuk penugasan kursus
        'course_assigned_at', // Ditambahkan untuk tanggal penugasan kursus
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at'  => 'datetime',
        'password'           => 'hashed',
        'is_verified'        => 'boolean',
        'birthdate'          => 'date',
        'course_assigned_at' => 'datetime', // Dicast sebagai datetime
    ];

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function murids()
    {
        return $this->belongsToMany(User::class, 'guru_murid', 'guru_id', 'murid_id');
    }

    public function gurus()
    {
        return $this->belongsToMany(User::class, 'guru_murid', 'murid_id', 'guru_id');
    }

    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'schedule_murid', 'murid_id', 'schedule_id');
    }

    public function jadwalGuru()
    {
        return $this->hasMany(Schedule::class, 'guru_id');
    }

    public function swimmingCourse()
    {
        return $this->belongsTo(SwimmingCourse::class);
    }

    /**
     * Get the registrations for the user.
     */
    public function registrations()
    {
        // Relasi ini akan mengambil semua pendaftaran yang dimiliki oleh user ini
        // dengan asumsi kolom foreign key di tabel 'registrations' adalah 'user_id'.
        return $this->hasMany(Registration::class, 'user_id');
    }


    public function getRemainingLessonDaysAttribute()
    {
        if (! $this->swimmingCourse || ! $this->course_assigned_at) {
            return null;
        }

        $durationWeeks = $this->swimmingCourse->duration;

        if (! is_numeric($durationWeeks) || $durationWeeks <= 0) {
            return 'Kursus telah kedaluwarsa atau durasi tidak valid.';
        }

        $assignedDate   = $this->course_assigned_at;
        $expirationDate = $assignedDate->copy()->addDays($durationWeeks * 7);

        if (now()->greaterThanOrEqualTo($expirationDate)) {
            return 'Kursus telah kedaluwarsa.';
        }

        $diff = now()->diff($expirationDate);

        $days = floor($diff->totalDays);

        $hours   = $diff->h;
        $minutes = $diff->i;
        $seconds = $diff->s;

        $parts = [];
        if ($days > 0) {
            $parts[] = $days . ' hari';
        }
        if ($hours > 0) {
            $parts[] = $hours . ' jam';
        }
        if ($minutes > 0) {
            $parts[] = $minutes . ' menit';
        }

        if (count($parts) > 0) {
            return implode(' ', $parts) . ' tersisa'; // String ini sudah menyertakan ' tersisa'
        } else {
            return 'Kurang dari 1 detik tersisa.';
        }
    }
}
