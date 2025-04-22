<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    /**
     * Show patient's submitted ratings
     */
    public function index()
    {
        $ratings = Rating::with(['doctor.user', 'appointment'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('patient.ratings.index', compact('ratings'));
    }
    
    /**
     * Show form to rate a completed appointment
     */
    public function create($appointmentId)
    {
        $appointment = Appointment::with('doctor.user')->findOrFail($appointmentId);
        
        // Check if user owns this appointment
        if ($appointment->UserID != Auth::id()) {
            return redirect()->route('patient.appointments.index')->with('error', 'You do not have permission to rate this appointment.');
        }
        
        // Check if appointment is completed
        if ($appointment->Status != 'completed') {
            return redirect()->route('patient.appointments.index')->with('error', 'You can only rate completed appointments.');
        }
        
        // Check if already rated
        $existingRating = Rating::where('user_id', Auth::id())
            ->where('appointment_id', $appointmentId)
            ->first();
            
        if ($existingRating) {
            return redirect()->route('patient.appointments.index')->with('error', 'You have already rated this appointment.');
        }
        
        return view('patient.ratings.create', compact('appointment'));
    }
    
    /**
     * Store a patient's rating
     */
    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'doctor_id' => 'required|exists:doctors,DoctorID',
            'doctor_rating' => 'nullable|integer|min:1|max:5',
            'service_rating' => 'nullable|integer|min:1|max:5',
            'cleanliness_rating' => 'nullable|integer|min:1|max:5',
            'staff_rating' => 'nullable|integer|min:1|max:5',
            'wait_time_rating' => 'nullable|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:500',
            'is_anonymous' => 'nullable|boolean',
        ]);
        
        // Check if the user has already rated this appointment
        $existingRating = Rating::where('user_id', Auth::id())
            ->where('appointment_id', $request->appointment_id)
            ->first();
            
        if ($existingRating) {
            return redirect()->route('patient.appointments.index')->with('error', 'You have already rated this appointment.');
        }
        
        // Verify the appointment belongs to the user
        $appointment = Appointment::findOrFail($request->appointment_id);
        if ($appointment->UserID != Auth::id()) {
            return redirect()->route('patient.appointments.index')->with('error', 'Invalid appointment.');
        }
        
        // Create the rating
        $rating = Rating::create([
            'user_id' => Auth::id(),
            'doctor_id' => $request->doctor_id,
            'appointment_id' => $request->appointment_id,
            'doctor_rating' => $request->doctor_rating,
            'service_rating' => $request->service_rating,
            'cleanliness_rating' => $request->cleanliness_rating,
            'staff_rating' => $request->staff_rating,
            'wait_time_rating' => $request->wait_time_rating,
            'feedback' => $request->feedback,
            'is_anonymous' => $request->has('is_anonymous'),
            'status' => 'pending',
        ]);
        
        return redirect()->route('patient.ratings.index')->with('success', 'Thank you for your feedback! Your rating has been submitted.');
    }
    
    /**
     * View doctor's public ratings
     */
    public function viewDoctorRatings($doctorId)
    {
        $doctor = Doctor::with('user')->findOrFail($doctorId);
        
        $ratings = Rating::with('user')
            ->where('doctor_id', $doctorId)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate(5);
            
        $avgRatings = [
            'doctor' => $ratings->whereNotNull('doctor_rating')->avg('doctor_rating'),
            'service' => $ratings->whereNotNull('service_rating')->avg('service_rating'),
            'cleanliness' => $ratings->whereNotNull('cleanliness_rating')->avg('cleanliness_rating'),
            'staff' => $ratings->whereNotNull('staff_rating')->avg('staff_rating'),
            'wait_time' => $ratings->whereNotNull('wait_time_rating')->avg('wait_time_rating'),
        ];
        
        return view('patient.ratings.doctor', compact('doctor', 'ratings', 'avgRatings'));
    }
} 