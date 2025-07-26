<?php
namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GuruCourseMeetingReminder extends Notification
{
    use Queueable;

    protected $murid;
    protected $sisaPertemuan;

    public function __construct(User $murid, int $sisaPertemuan)
    {
        $this->murid         = $murid;
        $this->sisaPertemuan = $sisaPertemuan;
    }

    public function via(object $notifiable): array
    {
        return ['database']; // Kirim sebagai notifikasi in-app
    }

    public function toArray(object $notifiable): array
    {
        $courseName = $this->murid->swimmingCourse->name ?? 'kursus';
        $message    = "Paket pertemuan murid '{$this->murid->name}' untuk kursus '{$courseName}' akan segera habis. Sisa {$this->sisaPertemuan} pertemuan lagi.";

        return [
            'type'       => 'meeting_reminder',
            'murid_id'   => $this->murid->id,
            'murid_name' => $this->murid->name,
            'message'    => $message,
            'action_url' => route('guru.murid.index'), // Link ke halaman manajemen murid
        ];
    }
}
