<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MuridExpiredNotification extends Notification
{
    use Queueable;

    public $murid;

    public function __construct($murid)
    {
        $this->murid = $murid;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Paket Les Anda Telah Expired')
            ->greeting('Halo ' . $this->murid->name . ',')
            ->line('Masa aktif les Anda telah berakhir. Silakan hubungi guru untuk memperpanjang.');
    }
}