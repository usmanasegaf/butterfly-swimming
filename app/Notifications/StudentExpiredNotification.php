<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StudentExpiredNotification extends Notification
{
    use Queueable;

    public $student;

    public function __construct($student)
    {
        $this->student = $student;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Paket Les Anda Telah Expired')
            ->greeting('Halo ' . $this->student->name . ',')
            ->line('Masa aktif les Anda telah berakhir. Silakan hubungi guru untuk memperpanjang.');
    }
}