<?php

namespace App\Http\Controllers\Student;

use App\Models\Student;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StudentController extends Controller
{
    // List semua murid
    public function index()
    {
        $students = Student::with('schedules')->get();
        return view('student.index', compact('students'));
    }

    // Detail murid & jadwal
    public function show($id)
    {
        $student = Student::with('schedules')->findOrFail($id);

        // Reminder expired
        $expired_in = $student->expired_at ? Carbon::now()->diffInDays($student->expired_at, false) : null;

        // Jadwal berikutnya
        $next_schedule = $student->schedules()
            ->where('date', '>=', Carbon::now())
            ->orderBy('date')
            ->orderBy('start_time')
            ->first();

        return view('student.show', compact('student', 'expired_in', 'next_schedule'));
    }

    // Form tambah murid
    public function create()
    {
        return view('student.create');
    }

    // Simpan murid baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'nullable|email',
            'phone' => 'nullable',
            'expired_at' => 'required|date'
        ]);
        Student::create($request->all());
        return redirect()->route('student.index')->with('success', 'Murid berhasil ditambahkan');
    }
}