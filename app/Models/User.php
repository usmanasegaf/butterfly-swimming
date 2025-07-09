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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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
        'email_verified_at'  => 'datetime',
        'password'           => 'hashed',
        'is_verified'        => 'boolean',
        'birthdate'          => 'date',
        'course_assigned_at' => 'datetime', // Dicast sebagai datetime
    ];

    /**
     * Relasi dengan guru yang membimbing murid ini (jika user adalah murid).
     */
    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    /**
     * Relasi dengan murid yang dibimbing oleh guru ini (jika user adalah guru).
     */
    public function murids()
    {
        return $this->belongsToMany(User::class, 'guru_murid', 'guru_id', 'murid_id');
    }

    public function gurus()
    {
        return $this->belongsToMany(User::class, 'guru_murid', 'murid_id', 'guru_id');
    }

    /**
     * Relasi dengan jadwal les (melalui tabel pivot schedule_murid).
     */
    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'schedule_murid', 'murid_id', 'schedule_id');
    }

    public function jadwalGuru()
    {
        return $this->hasMany(Schedule::class, 'guru_id');
    }

    /**
     * Relasi dengan SwimmingCourse yang ditetapkan kepada murid ini.
     */
    public function swimmingCourse()
    {
        return $this->belongsTo(SwimmingCourse::class);
    }

    /**
     * Accessor untuk menghitung sisa hari les.
     * Mengembalikan null jika tidak ada kursus yang ditetapkan atau tanggal penugasan.
     * Mengembalikan 0 jika sudah expired atau durasi tidak valid.
     */

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

        // ===============================================
        // --- PERUBAHAN DI SINI: Bulatkan ke bawah untuk hari ---
        // Menggunakan floor() untuk menghilangkan desimal pada hari.
        $days = floor($diff->totalDays);
        // ===============================================

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
