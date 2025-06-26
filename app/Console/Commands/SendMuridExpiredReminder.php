<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Murid;
use App\Notifications\MuridExpiredNotification;
use Carbon\Carbon;

class SendMuridExpiredReminder extends Command
{
    protected $signature = 'murid:send-expired-reminder';
    protected $description = 'Kirim email ke murid yang expired tepat 5 minggu';

public function handle()
{
    $murids = Murid::whereNotNull('expired_at')->whereNotNull('email')->get();

    foreach ($murids as $murid) {
        $days = Carbon::now()->diffInDays($murid->expired_at, false);

        if ($days === 5) {
            // 5 hari sebelum expired
            $murid->notify(new MuridExpiredNotification($murid, 'will_expire'));
        }
        if ($days === 0) {
            // Hari H expired
            $murid->notify(new MuridExpiredNotification($murid, 'expired'));
        }
    }

    $this->info('Notifikasi expired dikirim.');
}
}
