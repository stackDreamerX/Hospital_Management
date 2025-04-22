<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingViewController extends Controller
{
    /**
     * Display a listing of ratings for the current doctor
     */
    public function index()
    {
        // Get the doctor ID for the current user
        $doctor = Doctor::where('UserID', Auth::id())->first();
        
        if (!$doctor) {
            return redirect()->route('doctor.dashboard')->with('error', 'Doctor profile not found.');
        }
        
        $ratings = Rating::with(['user', 'appointment'])
            ->where('doctor_id', $doctor->DoctorID)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $avgRatings = [
            'doctor' => $ratings->whereNotNull('doctor_rating')->avg('doctor_rating'),
            'service' => $ratings->whereNotNull('service_rating')->avg('service_rating'),
            'cleanliness' => $ratings->whereNotNull('cleanliness_rating')->avg('cleanliness_rating'),
            'staff' => $ratings->whereNotNull('staff_rating')->avg('staff_rating'),
            'wait_time' => $ratings->whereNotNull('wait_time_rating')->avg('wait_time_rating'),
        ];
        
        // Get rating distribution
        $distribution = [
            1 => $ratings->where('doctor_rating', 1)->count(),
            2 => $ratings->where('doctor_rating', 2)->count(),
            3 => $ratings->where('doctor_rating', 3)->count(),
            4 => $ratings->where('doctor_rating', 4)->count(),
            5 => $ratings->where('doctor_rating', 5)->count(),
        ];
        
        return view('doctor.ratings.index', compact('ratings', 'avgRatings', 'distribution'));
    }
} 