<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view('pages.home');
    }

    public function sign_in()
    {
        return view('pages.sign_in');
    }

    public function sign_up()
    {
        return view('pages.sign_up');
    }

    public function home_dashboard(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);


        //    dd(['username' => $request->username, 'password' => $request->password]);


        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            // Điều hướng đến trang dashboard
            $request->session()->regenerate();
            
            // Set the last activity time for session timeout
            Session::put('lastActivityTime', \Carbon\Carbon::now());
            
            if (Auth::user()->RoleID === 'patient') {
                return redirect()->route('patient.dashboard');
            } elseif (Auth::user()->RoleID === 'doctor') {
                return redirect()->route('doctor.dashboard');
            } else {
                return redirect()->route('admin.dashboard');
            }
        } else {
            // Đăng nhập thất bại
            return redirect()->back()->withErrors(['message' => 'Tài khoản hoặc mật khẩu không đúng.']);
        }
    }

    public function home_logout()
    {
        // Đăng xuất người dùng
        Auth::logout();
        // Chuyển hướng về trang chủ
        return redirect('/')->with('success', 'You have been logged out successfully');
    }

    public function register(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'FullName' => 'required|string|max:100',
            'Email' => 'required|email|unique:users,Email',
            'password' => 'required|min:6|confirmed',
            'PhoneNumber' => 'required|string|max:15',
            'RoleID' => 'required|in:patient,doctor,administrator',
            'username' => 'required|string|max:50|unique:users,username',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        //dd($request->all());
        // Tạo user mới
        User::create([
            'RoleID' => $request->RoleID,
            'username' => $request->username,
            'FullName' => $request->FullName,
            'Email' => $request->Email,
            'password' => Hash::make($request->password),
            'PhoneNumber' => $request->PhoneNumber,

        ]);

        // Chuyển hướng sau khi đăng ký thành công
        return redirect('/sign-in')->with('success', 'Tài khoản đã được tạo thành công! Vui lòng đăng nhập.');
    }

    public function staff()
    {
        // Get list of specialties for the dropdown
        $specialties = Doctor::distinct()->pluck('Speciality')->toArray();
        
        // Get all doctors with their user information and ratings
        $doctors = Doctor::with(['user', 'ratings'])->get();
        
        // Calculate average rating for each doctor
        foreach ($doctors as $doctor) {
            $doctor->avgRating = $doctor->getAverageRatingAttribute() ?? 0;
            $doctor->ratingCount = $doctor->ratings->where('status', 'approved')
                            ->whereNotNull('doctor_rating')->count();
        }
        
        return view('pages.staff', [
            'doctors' => $doctors,
            'specialties' => $specialties,
            'search' => false
        ]);
    }

    public function searchDoctors(Request $request)
    {
        // Retrieve search inputs
        $name = $request->input('doctor_name', '');
        $specialty = $request->input('specialty');
        $location = $request->input('location');
        $insurance = $request->input('insurance');

        // Get list of specialties for the dropdown
        $specialties = Doctor::distinct()->pluck('Speciality')->toArray();

        // Start the query with doctor relationships
        $query = Doctor::with(['user', 'ratings']);

        // Apply filters if present
        if ($specialty) {
            $query->where('Speciality', $specialty);
        }

        // Name search needs to check the user table
        if ($name) {
            $query->whereHas('user', function($q) use ($name) {
                $q->where('FullName', 'LIKE', "%$name%");
            });
        }

        // For location and insurance, you'd need to add these to your database schema
        // This is placeholder code assuming these fields exist or will exist
        if ($location) {
            // $query->where('Location', $location);
            // Comment out until location field is added to schema
        }

        if ($insurance) {
            // $query->where('Insurance', $insurance);
            // Comment out until insurance field is added to schema
        }

        // Fetch filtered results
        $doctors = $query->get();
        
        // Calculate average rating for each doctor
        foreach ($doctors as $doctor) {
            $doctor->avgRating = $doctor->getAverageRatingAttribute() ?? 0;
            $doctor->ratingCount = $doctor->ratings->where('status', 'approved')
                            ->whereNotNull('doctor_rating')->count();
        }

        // Return the view with the results
        return view('pages.staff', [
            'doctors' => $doctors,
            'specialties' => $specialties,
            'search' => true
        ]);
    }


    public function locations()
    {
        return view('pages.locations');
    }

    public function patients()
    {
        return view('pages.patients');
    }

    public function appointments()
    {
        if (!Auth::check()) {
            return redirect('/sign-in')->with('message', 'Please login to access appointments');
        }

        return redirect('/patient/dashboard');
    }

    public function doctorProfile($id)
    {
        // Find the doctor by ID with related data
        $doctor = Doctor::with(['user', 'ratings' => function($query) {
            $query->where('status', 'approved')
                  ->whereNotNull('doctor_rating');
        }])->findOrFail($id);
        
        // Calculate average rating
        $doctor->avgRating = $doctor->getAverageRatingAttribute() ?? 0;
        $doctor->ratingCount = $doctor->ratings->count();
        
        // Get recent reviews (latest 5)
        $recentReviews = $doctor->ratings()
            ->where('status', 'approved')
            ->whereNotNull('doctor_rating')
            ->with('user')
            ->latest()
            ->limit(5)
            ->get();
        
        // Get the doctor's upcoming appointments to show availability
        // This is simplified - in a real app you would check against available slots
        $upcomingAppointments = $doctor->appointments()
            ->where('AppointmentDate', '>=', now()->format('Y-m-d'))
            ->orderBy('AppointmentDate')
            ->orderBy('AppointmentTime')
            ->get()
            ->groupBy('AppointmentDate');
            
        return view('pages.doctor_profile', [
            'doctor' => $doctor,
            'recentReviews' => $recentReviews,
            'upcomingAppointments' => $upcomingAppointments
        ]);
    }
}
// php artisan make:controller HomeController
