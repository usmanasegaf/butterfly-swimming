<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Pastikan ini diimpor
use App\Models\Schedule; // Pastikan ini diimpor
use Carbon\Carbon; // Pastikan ini diimpor

class MuridDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Ini adalah instance user yang sedang login (murid)
        $assignedCourse = $user->swimmingCourse; // Mengambil kursus yang ditetapkan (relasi dari User model)

        $nextSchedule = null;
        $location = null;

        if ($assignedCourse) {
            // Ambil lokasi dari kursus yang ditetapkan (asumsi ada di SwimmingCourse atau Schedule)
            // Dari output.txt, Location ada, dan Schedule punya location_id,
            // jadi kita bisa ambil lokasi dari jadwal selanjutnya.
            // Atau jika lokasi kursus spesifik: $location = $assignedCourse->location_name; (jika ada kolomnya)
            // Untuk sekarang, kita akan ambil dari jadwal les selanjutnya.

            // Mencari jadwal les selanjutnya
            // Pertama, ambil semua jadwal yang terkait dengan kursus yang ditetapkan DAN murid ini
            // (Melalui pivot schedule_murid, karena murid hanya menghadiri jadwal tertentu)
            // Atau, jika jadwal ditentukan per kursus dan semua murid di kursus itu ikut jadwal sama:
            $courseSchedules = $assignedCourse->schedules; // Asumsi ada relasi schedules() di SwimmingCourse

            $now = Carbon::now();
            $todayDayOfWeek = $now->dayOfWeekIso; // 1 (Senin) - 7 (Minggu)

            foreach ($courseSchedules as $schedule) {
                $scheduleDayOfWeek = $schedule->day_of_week; // Asumsi ini juga 1-7
                $scheduleTime = Carbon::createFromFormat('H:i:s', $schedule->start_time_of_day);

                $nextOccurrence = null;

                // Jika hari jadwal adalah hari ini
                if ($scheduleDayOfWeek == $todayDayOfWeek) {
                    // Jika waktu jadwal sudah lewat, cek untuk minggu depan
                    if ($scheduleTime->isPast()) {
                        $nextOccurrence = $now->copy()->addWeek()->startOfWeek()->addDays($scheduleDayOfWeek - 1)->setTime($scheduleTime->hour, $scheduleTime->minute, $scheduleTime->second);
                    } else {
                        // Jika waktu jadwal belum lewat hari ini
                        $nextOccurrence = $now->copy()->setTime($scheduleTime->hour, $scheduleTime->minute, $scheduleTime->second);
                    }
                }
                // Jika hari jadwal di masa depan dalam minggu ini
                else if ($scheduleDayOfWeek > $todayDayOfWeek) {
                    $nextOccurrence = $now->copy()->startOfWeek()->addDays($scheduleDayOfWeek - 1)->setTime($scheduleTime->hour, $scheduleTime->minute, $scheduleTime->second);
                }
                // Jika hari jadwal di masa lalu dalam minggu ini, cek untuk minggu depan
                else {
                    $nextOccurrence = $now->copy()->addWeek()->startOfWeek()->addDays($scheduleDayOfWeek - 1)->setTime($scheduleTime->hour, $scheduleTime->minute, $scheduleTime->second);
                }

                // Inisialisasi nextSchedule jika ini adalah yang pertama atau lebih dekat
                if ($nextOccurrence && ($nextSchedule === null || $nextOccurrence->lessThan($nextSchedule->occurrence))) {
                    $nextSchedule = (object)[
                        'schedule' => $schedule,
                        'occurrence' => $nextOccurrence
                    ];
                }
            }

            // Jika ditemukan jadwal selanjutnya, ambil lokasinya
            if ($nextSchedule && $nextSchedule->schedule->location) {
                $location = $nextSchedule->schedule->location->name; // Asumsi relasi location() ada di model Schedule
            }
        }

        return view('murid.dashboard', compact('user', 'assignedCourse', 'nextSchedule', 'location'));
    }
}