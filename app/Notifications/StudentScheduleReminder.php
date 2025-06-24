<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StudentScheduleReminder extends Notification
{
    use Queueable;

    public $schedule;

    public function __construct($schedule)
    {
        $this->schedule = $schedule;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Jadwal les Anda akan dimulai pada ' .
                $this->schedule->date . ' pukul ' . $this->schedule->start_time . ' di ' . $this->schedule->location,
        ];
    }
}