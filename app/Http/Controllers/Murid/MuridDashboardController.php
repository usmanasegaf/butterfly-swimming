<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Schedule;
use Carbon\Carbon;

class MuridDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Ini adalah instance user yang sedang login (murid)
        $assignedCourse = $user->swimmingCourse; // Mengambil kursus yang ditetapkan (relasi dari User model)

        $nextSchedule = null;
        $location = null;

        if ($assignedCourse) {
            // Ambil ID dari semua guru yang terasosiasi dengan murid ini melalui tabel pivot guru_murid
            // Memastikan kolom 'id' tidak ambigu dengan menyebutkan 'users.id'
            $associatedGuruIds = $user->gurus()->pluck('users.id')->toArray();

            // Hanya lanjutkan jika murid memiliki guru yang terasosiasi
            if (!empty($associatedGuruIds)) {
                // Query langsung ke tabel schedules
                // Filter berdasarkan swimming_course_id yang ditugaskan kepada murid
                // DAN filter berdasarkan guru_id yang mengajar jadwal tersebut, harus termasuk dalam guru yang terasosiasi dengan murid
                $potentialSchedules = Schedule::where('swimming_course_id', $assignedCourse->id)
                                            ->whereIn('guru_id', $associatedGuruIds)
                                            ->with(['swimmingCourse', 'location', 'guru']) // Muat relasi yang dibutuhkan
                                            ->get(); // Ambil semua jadwal yang cocok

                $now = Carbon::now();
                $todayDayOfWeek = $now->dayOfWeekIso; // 1 (Senin) - 7 (Minggu)

                foreach ($potentialSchedules as $schedule) {
                    $scheduleDayOfWeek = $schedule->day_of_week;
                    $scheduleTime = Carbon::createFromFormat('H:i:s', $schedule->start_time_of_day);

                    $nextOccurrence = null;

                    // Logika untuk menemukan kejadian selanjutnya dari jadwal spesifik ini
                    // Ini akan mencari waktu terdekat dari masa sekarang
                    if ($scheduleDayOfWeek == $todayDayOfWeek) {
                        // Jika hari ini adalah hari jadwal, periksa apakah waktunya di masa depan
                        if ($scheduleTime->isPast()) {
                            // Jika waktu sudah lewat hari ini, kejadian selanjutnya adalah minggu depan
                            $nextOccurrence = $now->copy()->addWeek()->startOfWeek(Carbon::MONDAY)->addDays($scheduleDayOfWeek - 1)->setTime($scheduleTime->hour, $scheduleTime->minute, $scheduleTime->second);
                        } else {
                            // Jika waktu di masa depan hari ini, itu adalah hari ini
                            $nextOccurrence = $now->copy()->setTime($scheduleTime->hour, $scheduleTime->minute, $scheduleTime->second);
                        }
                    }
                    else if ($scheduleDayOfWeek > $todayDayOfWeek) {
                        // Jika hari jadwal lebih lambat di minggu ini (misal: hari ini Senin, jadwal hari Rabu)
                        $nextOccurrence = $now->copy()->startOfWeek(Carbon::MONDAY)->addDays($scheduleDayOfWeek - 1)->setTime($scheduleTime->hour, $scheduleTime->minute, $scheduleTime->second);
                    }
                    else {
                        // Jika hari jadwal lebih awal di minggu ini (sudah lewat, misal: hari ini Rabu, jadwal hari Senin), kejadian selanjutnya adalah minggu depan
                        $nextOccurrence = $now->copy()->addWeek()->startOfWeek(Carbon::MONDAY)->addDays($scheduleDayOfWeek - 1)->setTime($scheduleTime->hour, $scheduleTime->minute, $scheduleTime->second);
                    }

                    // Bandingkan dengan jadwal terdekat yang sudah ditemukan
                    if ($nextOccurrence && ($nextSchedule === null || $nextOccurrence->lessThan($nextSchedule->occurrence))) {
                        $nextSchedule = (object)[
                            'schedule' => $schedule,
                            'occurrence' => $nextOccurrence
                        ];
                    }
                }
            } else {
                // Jika tidak ada guru yang terasosiasi, tidak ada jadwal yang bisa ditampilkan
                // $nextSchedule akan tetap null
            }
        } else {
            // Jika murid belum ditugaskan ke kursus manapun, tidak ada jadwal yang bisa ditampilkan
            // $nextSchedule akan tetap null
        }

        // Jika ditemukan jadwal selanjutnya, ambil lokasinya
        if ($nextSchedule && $nextSchedule->schedule->location) {
            $location = $nextSchedule->schedule->location->name;
        }

        return view('murid.dashboard', compact('user', 'nextSchedule', 'location'));
    }
}
