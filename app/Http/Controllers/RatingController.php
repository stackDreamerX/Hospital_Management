<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    /**
     * Display a listing of all ratings
     */
    public function index()
    {
        // For admin view - show all ratings with pagination
        $ratings = Rating::with(['user', 'doctor.user', 'appointment'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('ratings.index', compact('ratings'));
    }
    
    /**
     * Store a new rating
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
            return redirect()->back()->with('error', 'You have already rated this appointment.');
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
            'is_anonymous' => $request->is_anonymous ? true : false,
            'status' => 'pending',
        ]);
        
        return redirect()->back()->with('success', 'Thank you for your feedback! Your rating has been submitted.');
    }
    
    /**
     * Update rating status (for admin)
     */
    public function updateStatus(Request $request, Rating $rating)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);
        
        $rating->update([
            'status' => $request->status,
        ]);
        
        return redirect()->back()->with('success', 'Rating status updated successfully.');
    }
    
    /**
     * Get rating form for a specific appointment
     */
    public function showRatingForm($appointmentId)
    {
        $appointment = Appointment::with('doctor')->findOrFail($appointmentId);
        
        // Check if user owns this appointment
        if ($appointment->UserID != Auth::id()) {
            return redirect()->back()->with('error', 'You do not have permission to rate this appointment.');
        }
        
        // Check if appointment is completed
        if ($appointment->status != 'completed') {
            return redirect()->back()->with('error', 'You can only rate completed appointments.');
        }
        
        // Check if already rated
        $existingRating = Rating::where('user_id', Auth::id())
            ->where('appointment_id', $appointmentId)
            ->first();
            
        if ($existingRating) {
            return redirect()->back()->with('error', 'You have already rated this appointment.');
        }
        
        return view('ratings.create', compact('appointment'));
    }
    
    /**
     * Get doctor ratings summary
     */
    public function doctorRatings($doctorId)
    {
        $doctor = Doctor::with('user')->findOrFail($doctorId);
        
        $ratings = Rating::where('doctor_id', $doctorId)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate(5);
            
        $avgRatings = [
            'doctor' => $ratings->whereNotNull('doctor_rating')->avg('doctor_rating'),
            'service' => $ratings->whereNotNull('service_rating')->avg('service_rating'),
            'cleanliness' => $ratings->whereNotNull('cleanliness_rating')->avg('cleanliness_rating'),
            'staff' => $ratings->whereNotNull('staff_rating')->avg('staff_rating'),
            'wait_time' => $ratings->whereNotNull('wait_time_rating')->avg('wait_time_rating'),
            'overall' => $ratings->avg(function($rating) {
                $sum = 0;
                $count = 0;
                
                if ($rating->doctor_rating) { $sum += $rating->doctor_rating; $count++; }
                if ($rating->service_rating) { $sum += $rating->service_rating; $count++; }
                if ($rating->cleanliness_rating) { $sum += $rating->cleanliness_rating; $count++; }
                if ($rating->staff_rating) { $sum += $rating->staff_rating; $count++; }
                if ($rating->wait_time_rating) { $sum += $rating->wait_time_rating; $count++; }
                
                return $count > 0 ? $sum / $count : null;
            }),
        ];
        
        return view('ratings.doctor', compact('doctor', 'ratings', 'avgRatings'));
    }
} 