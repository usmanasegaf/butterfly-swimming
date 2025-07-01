<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class GuruCourseController extends Controller
{
    public function index()
    {
        // Ambil semua kursus yang dipegang guru (dari jadwal yang dibuat guru)
        $courses = Auth::user()
            ->schedules()
            ->with('swimmingCourse')
            ->get()
            ->pluck('swimmingCourse')
            ->unique('id')
            ->values();

        return view('guru.kursus.index', compact('courses'));
    }

    public function show($id)
    {
        $course = \App\Models\SwimmingCourse::findOrFail($id);
        return view('guru.kursus.show', compact('course'));
    }
}