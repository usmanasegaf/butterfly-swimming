<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User; // Import model User
use App\Models\SwimmingCourse; // Import model SwimmingCourse

class MuridCourseReminderNotification extends Notification
{
    use Queueable;

    protected $murid;
    protected $daysRemaining;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $murid, $daysRemaining)
    {
        $this->murid = $murid;
        $this->daysRemaining = $daysRemaining;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Hanya menggunakan notifikasi in-app (database)
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $courseName = $this->murid->swimmingCourse->name ?? 'Kursus Anda';
        $message = "Sisa waktu les Anda untuk kursus '{$courseName}' tinggal {$this->daysRemaining} hari lagi. Segera perpanjang kursus Anda!";

        return [
            'type' => 'course_reminder',
            'course_id' => $this->murid->swimming_course_id,
            'course_name' => $courseName,
            'days_remaining' => $this->daysRemaining,
            'message' => $message,
            'action_url' => route('murid.index'), // Link ke halaman "Kursus Saya" murid
        ];
    }
}
