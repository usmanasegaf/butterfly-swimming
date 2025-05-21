<?php

namespace App\Http\Controllers\User;

use App\Models\Registration;
use App\Models\SwimmingCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;


class RegistrationController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('permission:view own registrations')->only(['myRegistrations', 'show']);
        $this->middleware('permission:register to course')->only(['create', 'store']);
        $this->middleware('permission:cancel own registration')->only(['cancel']);
    }

    /**
     * Display a listing of the user's registrations.
     */
    public function myRegistrations()
    {
        // Get current user's registrations with course details
        $registrations = Auth::user()->registrations()
                            ->with('swimmingCourse')
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('user.registrations.index', compact('registrations'));
    }

    /**
     * Show the form for creating a new registration.
     */
    public function create()
    {
        // Get only active courses for registration
        $courses = SwimmingCourse::where('is_active', true)
                                ->orderBy('name')
                                ->get();

        return view('user.registrations.create', compact('courses'));
    }

    /**
     * Store a newly created registration in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'swimming_course_id' => 'required|exists:swimming_courses,id',
            'start_date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string|max:500',
        ]);

        // Get the course
        $course = SwimmingCourse::findOrFail($request->swimming_course_id);
        
        // Calculate end date based on course duration (in weeks)
        $startDate = new \DateTime($request->start_date);
        $endDate = clone $startDate;
        $endDate->modify('+' . $course->duration . ' weeks');

        // Create new registration
        $registration = Registration::create([
            'user_id' => Auth::id(),
            'swimming_course_id' => $request->swimming_course_id,
            'start_date' => $request->start_date,
            'end_date' => $endDate->format('Y-m-d'),
            'status' => 'Pending', // Default status is pending
            'payment_status' => 'Unpaid', // Default payment status
            'notes' => $request->notes,
        ]);

        return redirect()->route('my-registrations')
            ->with('success', 'Pendaftaran kursus renang berhasil dibuat. Mohon tunggu persetujuan admin.');
    }

    /**
     * Display the specified registration.
     */
    public function show($id)
    {
        $registration = Registration::where('id', $id)
                            ->where('user_id', Auth::id()) // Ensure user can only view their own
                            ->with('swimmingCourse')
                            ->firstOrFail();

        return view('user.registrations.show', compact('registration'));
    }

    /**
     * Cancel the specified registration.
     */
    public function cancel($id)
    {
        $registration = Registration::where('id', $id)
                            ->where('user_id', Auth::id()) // Ensure user can only cancel their own
                            ->firstOrFail();
                            
        // Only Pending registrations can be cancelled
        if ($registration->status !== 'Pending') {
            return redirect()->back()->with('error', 'Hanya pendaftaran dengan status Pending yang dapat dibatalkan.');
        }
        
        $registration->status = 'Cancelled';
        $registration->save();

        return redirect()->route('my-registrations')
            ->with('success', 'Pendaftaran berhasil dibatalkan.');
    }
}