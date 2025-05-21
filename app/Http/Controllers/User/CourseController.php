<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SwimmingCourse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of available swimming courses.
     */
    public function index()
    {
        // Get only active courses
        $courses = SwimmingCourse::where('is_active', true)
                              ->orderBy('level')
                              ->orderBy('name')
                              ->paginate(9);
                              
        return view('user.courses.index', compact('courses'));
    }
    
    /**
     * Display the specified swimming course.
     */
    public function show($id)
    {
        $course = SwimmingCourse::where('is_active', true)
                             ->findOrFail($id);
                             
        return view('user.courses.show', compact('course'));
    }
}