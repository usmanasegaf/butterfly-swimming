<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\SwimmingCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $registrations = Registration::with(['user', 'swimmingCourse'])->latest()->get();

        return view('registrations.index', [
            'registrations' => $registrations
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = SwimmingCourse::where('is_active', true)->get();
        
        return view('registrations.create', [
            'courses' => $courses
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'swimming_course_id' => 'required|exists:swimming_courses,id',
            'start_date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string'
        ])->validate();

        $course = SwimmingCourse::findOrFail($request->swimming_course_id);
        
        // Calculate end date based on course duration
        $startDate = new \DateTime($request->start_date);
        $endDate = clone $startDate;
        $endDate->modify('+' . $course->duration . ' weeks');

        $registration = new Registration();
        $registration->user_id = Auth::id();
        $registration->swimming_course_id = $request->swimming_course_id;
        $registration->start_date = $request->start_date;
        $registration->end_date = $endDate->format('Y-m-d');
        $registration->notes = $request->notes;
        $registration->save();

        return redirect()->route('my-registrations')->with('success', 'Pendaftaran kursus berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Registration $registration)
    {
        return view('registrations.show', [
            'registration' => $registration
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateStatus(Request $request, Registration $registration)
    {
        Validator::make($request->all(), [
            'status' => 'required|in:pending,approved,rejected,completed',
            'payment_status' => 'required|in:unpaid,paid,refunded',
        ])->validate();

        $registration->status = $request->status;
        $registration->payment_status = $request->payment_status;
        $registration->save();

        return redirect()->route('registrations.show', $registration->id)->with('success', 'Status pendaftaran berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Registration $registration)
    {
        $registration->delete();

        return redirect()->route('registrations.index')->with('success', 'Pendaftaran kursus berhasil dihapus');
    }

    /**
     * Display a listing of the user's registrations.
     */
    public function myRegistrations()
    {
        $registrations = Auth::user()->registrations()->with('swimmingCourse')->latest()->get();

        return view('registrations.my-registrations', [
            'registrations' => $registrations
        ]);
    }
}
