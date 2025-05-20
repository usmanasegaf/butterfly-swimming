<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SwimmingCourse;
use Illuminate\Http\Request;

class SwimmingCourseManagementController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('permission:view swimming courses')->only(['index', 'show']);
        $this->middleware('permission:create swimming course')->only(['create', 'store']);
        $this->middleware('permission:edit swimming course')->only(['edit', 'update']);
        $this->middleware('permission:delete swimming course')->only('destroy');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = SwimmingCourse::orderBy('name')->paginate(10);
        return view('admin.swimming-course-management.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.swimming-course-management.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer|min:0',
            'duration' => 'required|integer|min:1',
            'sessions_per_week' => 'required|integer|min:1',
            'max_participants' => 'nullable|integer|min:1',
            'instructor' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);
        
        SwimmingCourse::create($validated);
        
        return redirect()->route('swimming-course-management.index')
            ->with('success', 'Kursus renang berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(SwimmingCourse $swimmingCourseManagement)
    {
        return view('admin.swimming-course-management.show', [
            'course' => $swimmingCourseManagement
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SwimmingCourse $swimmingCourseManagement)
    {
        return view('admin.swimming-course-management.edit', [
            'course' => $swimmingCourseManagement
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SwimmingCourse $swimmingCourseManagement)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer|min:0',
            'duration' => 'required|integer|min:1',
            'sessions_per_week' => 'required|integer|min:1',
            'max_participants' => 'nullable|integer|min:1',
            'instructor' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);
        
        $swimmingCourseManagement->update($validated);
        
        return redirect()->route('swimming-course-management.index')
            ->with('success', 'Kursus renang berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SwimmingCourse $swimmingCourseManagement)
    {
        $swimmingCourseManagement->delete();
        
        return redirect()->route('swimming-course-management.index')
            ->with('success', 'Kursus renang berhasil dihapus!');
    }
}