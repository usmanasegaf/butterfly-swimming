<?php

namespace App\Http\Controllers;

use App\Models\SwimmingCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SwimmingCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = SwimmingCourse::latest()->get();

        return view('swimming-courses.index', [
            'courses' => $courses
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('swimming-courses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'level' => 'required|string|max:50',
            'description' => 'required|string',
            'price' => 'required|integer|min:0',
            'duration' => 'required|integer|min:1',
            'sessions_per_week' => 'required|integer|min:1',
            'max_participants' => 'nullable|integer|min:1',
            'instructor' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ])->validate();

        $course = new SwimmingCourse();
        $course->name = $request->name;
        $course->level = $request->level;
        $course->description = $request->description;
        $course->price = $request->price;
        $course->duration = $request->duration;
        $course->sessions_per_week = $request->sessions_per_week;
        $course->max_participants = $request->max_participants;
        $course->instructor = $request->instructor;
        $course->is_active = $request->has('is_active');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $course->image = $imageName;
        }

        $course->save();

        return redirect()->route('swimming-courses.index')->with('success', 'Kursus renang berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(SwimmingCourse $swimmingCourse)
    {
        return view('swimming-courses.show', [
            'course' => $swimmingCourse
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SwimmingCourse $swimmingCourse)
    {
        return view('swimming-courses.edit', [
            'course' => $swimmingCourse
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SwimmingCourse $swimmingCourse)
    {
        Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'level' => 'required|string|max:50',
            'description' => 'required|string',
            'price' => 'required|integer|min:0',
            'duration' => 'required|integer|min:1',
            'sessions_per_week' => 'required|integer|min:1',
            'max_participants' => 'nullable|integer|min:1',
            'instructor' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ])->validate();

        $swimmingCourse->name = $request->name;
        $swimmingCourse->level = $request->level;
        $swimmingCourse->description = $request->description;
        $swimmingCourse->price = $request->price;
        $swimmingCourse->duration = $request->duration;
        $swimmingCourse->sessions_per_week = $request->sessions_per_week;
        $swimmingCourse->max_participants = $request->max_participants;
        $swimmingCourse->instructor = $request->instructor;
        $swimmingCourse->is_active = $request->has('is_active');

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($swimmingCourse->image && file_exists(public_path('images/' . $swimmingCourse->image))) {
                unlink(public_path('images/' . $swimmingCourse->image));
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $swimmingCourse->image = $imageName;
        }

        $swimmingCourse->save();

        return redirect()->route('swimming-courses.index')->with('success', 'Kursus renang berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SwimmingCourse $swimmingCourse)
    {
        // Hapus gambar jika ada
        if ($swimmingCourse->image && file_exists(public_path('images/' . $swimmingCourse->image))) {
            unlink(public_path('images/' . $swimmingCourse->image));
        }

        $swimmingCourse->delete();

        return redirect()->route('swimming-courses.index')->with('success', 'Kursus renang berhasil dihapus');
    }
}
