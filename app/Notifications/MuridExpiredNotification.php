<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\User; // Import model User
use App\Models\SwimmingCourse; // Import model SwimmingCourse (jika ingin menampilkan nama kursus)

class MuridExpiredNotification extends Notification
{
    use Queueable;

    public $murid;
    public $type; // Tambahkan properti ini untuk membedakan 'will_expire' dan 'expired'

    /**
     * Create a new notification instance.
     */
    public function __construct(User $murid, $type = 'expired') // Tambahkan $type sebagai parameter
    {
        $this->murid = $murid;
        $this->type = $type; // Simpan tipe notifikasi
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database']; // Tambahkan 'database' untuk in-app notification
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        $subject = ($this->type === 'will_expire') ? 'Pengingat: Paket Les Anda Akan Segera Berakhir' : 'Pemberitahuan: Paket Les Anda Telah Berakhir';
        $greeting = 'Halo ' . $this->murid->name . ',';
        $line1 = '';
        $callToAction = '';

        $courseName = $this->murid->swimmingCourse->name ?? 'kursus renang Anda';

        if ($this->type === 'will_expire') {
            $line1 = "Masa aktif {$courseName} Anda akan segera berakhir dalam 5 hari. Segera perpanjang untuk melanjutkan les tanpa gangguan.";
            $callToAction = 'Perpanjang Sekarang'; // Anda bisa mengarahkan ke halaman perpanjangan
        } else { // type === 'expired'
            $line1 = "Masa aktif {$courseName} Anda telah berakhir. Anda tidak dapat lagi mengikuti les. Silakan hubungi admin atau guru Anda untuk informasi perpanjangan.";
            $callToAction = 'Hubungi Admin'; // Atau link ke halaman kontak
        }

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject($subject)
            ->greeting($greeting)
            ->line($line1)
            ->action($callToAction, url('/')) // Ganti URL ini sesuai halaman perpanjangan/kontak
            ->line('Terima kasih atas perhatiannya.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $courseName = $this->murid->swimmingCourse->name ?? 'Kursus Anda';
        $message = '';
        $actionUrl = route('murid.index'); // Default link ke halaman kursus saya

        if ($this->type === 'will_expire') {
            $message = "Masa aktif kursus '{$courseName}' Anda akan berakhir dalam 5 hari. Segera perpanjang!";
        } else { // type === 'expired'
            $message = "Masa aktif kursus '{$courseName}' Anda telah berakhir. Anda tidak dapat lagi mengikuti les.";
        }

        return [
            'type' => 'course_expiration',
            'course_id' => $this->murid->swimming_course_id,
            'course_name' => $courseName,
            'status' => $this->type,
            'message' => $message,
            'action_url' => $actionUrl,
        ];
    }
}
