<?php
namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MuridCourseMeetingExpired extends Notification
{
    use Queueable;

    protected $murid;
    protected $courseName;

    public function __construct(User $murid, string $courseName)
    {
        $this->murid      = $murid;
        $this->courseName = $courseName;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $messageForGuru  = "Paket pertemuan murid '{$this->murid->name}' untuk kursus '{$this->courseName}' telah habis.";
        $messageForMurid = "Paket pertemuan Anda untuk kursus '{$this->courseName}' telah habis. Silakan hubungi guru Anda untuk memperpanjang.";

        $isGuru = $notifiable->role === 'guru';

        return [
            'type'       => 'meeting_expired',
            'murid_id'   => $this->murid->id,
            'murid_name' => $this->murid->name,
            'message'    => $isGuru ? $messageForGuru : $messageForMurid,
            'action_url' => $isGuru ? route('guru.murid.index') : route('murid.dashboard'),
        ];
    }
}
