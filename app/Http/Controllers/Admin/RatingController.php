<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RatingController extends Controller
{
    /**
     * Display a listing of all ratings
     */
    public function index()
    {
        $ratings = Rating::with(['user', 'doctor.user', 'appointment'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.ratings.index', compact('ratings'));
    }
    
    /**
     * Update a rating's status (approve/reject)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);
        
        $rating = Rating::findOrFail($id);
        $rating->update([
            'status' => $request->status,
        ]);
        
        return redirect()->route('admin.ratings.index')->with('success', 'Rating status updated successfully.');
    }
    
    /**
     * Delete a rating
     */
    public function destroy($id)
    {
        $rating = Rating::findOrFail($id);
        $rating->delete();
        
        return redirect()->route('admin.ratings.index')->with('success', 'Rating deleted successfully.');
    }
    
    /**
     * Display ratings dashboard with statistics
     */
    public function dashboard()
    {
        // Overall clinic ratings
        $clinicRatings = [
            'service' => Rating::where('status', 'approved')->whereNotNull('service_rating')->avg('service_rating'),
            'cleanliness' => Rating::where('status', 'approved')->whereNotNull('cleanliness_rating')->avg('cleanliness_rating'),
            'staff' => Rating::where('status', 'approved')->whereNotNull('staff_rating')->avg('staff_rating'),
            'wait_time' => Rating::where('status', 'approved')->whereNotNull('wait_time_rating')->avg('wait_time_rating'),
        ];
        
        // Top rated doctors
        $topDoctors = Doctor::with('user')
            ->select('doctors.*', DB::raw('AVG(ratings.doctor_rating) as average_rating'))
            ->leftJoin('ratings', function($join) {
                $join->on('doctors.DoctorID', '=', 'ratings.doctor_id')
                    ->where('ratings.status', '=', 'approved')
                    ->whereNotNull('ratings.doctor_rating');
            })
            ->groupBy('doctors.DoctorID')
            ->havingRaw('AVG(ratings.doctor_rating) IS NOT NULL')
            ->orderByDesc('average_rating')
            ->limit(5)
            ->get();
            
        // Recent ratings
        $recentRatings = Rating::with(['user', 'doctor.user'])
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Pending ratings count
        $pendingCount = Rating::where('status', 'pending')->count();
        
        // Monthly rating trends
        $monthlyTrends = Rating::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('AVG(doctor_rating) as avg_doctor_rating'),
                DB::raw('AVG(service_rating) as avg_service_rating'),
                DB::raw('COUNT(*) as count')
            )
            ->where('status', 'approved')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();
            
        return view('admin.ratings.dashboard', compact(
            'clinicRatings', 
            'topDoctors', 
            'recentRatings', 
            'pendingCount',
            'monthlyTrends'
        ));
    }
    
    /**
     * Display doctor-specific ratings summary
     */
    public function doctorRatings($doctorId)
    {
        $doctor = Doctor::with('user')->findOrFail($doctorId);
        
        $ratings = Rating::with('user')
            ->where('doctor_id', $doctorId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $avgRatings = [
            'doctor' => $ratings->where('status', 'approved')->whereNotNull('doctor_rating')->avg('doctor_rating'),
            'service' => $ratings->where('status', 'approved')->whereNotNull('service_rating')->avg('service_rating'),
            'cleanliness' => $ratings->where('status', 'approved')->whereNotNull('cleanliness_rating')->avg('cleanliness_rating'),
            'staff' => $ratings->where('status', 'approved')->whereNotNull('staff_rating')->avg('staff_rating'),
            'wait_time' => $ratings->where('status', 'approved')->whereNotNull('wait_time_rating')->avg('wait_time_rating'),
        ];
        
        // Rating distribution (1-5 stars count)
        $distribution = [
            'doctor' => [
                1 => $ratings->where('status', 'approved')->where('doctor_rating', 1)->count(),
                2 => $ratings->where('status', 'approved')->where('doctor_rating', 2)->count(),
                3 => $ratings->where('status', 'approved')->where('doctor_rating', 3)->count(),
                4 => $ratings->where('status', 'approved')->where('doctor_rating', 4)->count(),
                5 => $ratings->where('status', 'approved')->where('doctor_rating', 5)->count(),
            ]
        ];
        
        return view('admin.ratings.doctor', compact('doctor', 'ratings', 'avgRatings', 'distribution'));
    }
} 