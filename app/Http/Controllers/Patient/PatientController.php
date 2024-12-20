<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index() {
        return view('patient.dashboard');
    }

    public function profile() {
        echo 1;
    }

    public function settings() {
        echo 1;
    }

    public function logout() {
        // Đăng xuất người dùng
        Auth::logout();
    
        // Chuyển hướng về trang chủ
        return redirect('/dashboard')->with('message', 'You have been logged out successfully.');
    }

    public function patients() {
        echo 1;
    }

    public function patients_details() {
        echo 1;
    }
   
}
