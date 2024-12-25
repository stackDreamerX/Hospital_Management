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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class HomeController extends Controller
{
    public function index()
    {
        return view('pages.home');
    }

    public function sign_in() {
        return view('pages.sign_in');
   }

    public function sign_up() {
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
            if (Auth::user()->RoleID === 'patient') {
                return redirect()->route('patient.dashboard');
            } elseif (Auth::user()->RoleID === 'doctor') {
                return redirect()->route('doctor.dashboard');
            } else {
                return redirect()->route('admin.dashboard');
            }
        }
        else {
            // Đăng nhập thất bại
            return redirect()->back()->withErrors(['message' => 'Tài khoản hoặc mật khẩu không đúng.']);
        }
       
    }

    public function home_logout() {
        // Đăng xuất người dùng
        Auth::logout();
        // Chuyển hướng về trang chủ
        return redirect('/trang-chu')->withErrors(['message' => 'You have been logged out successfully']);
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
        return redirect('/sign-in')->with('success', 'Account created successfully! Please log in.');
    }

    public function staff()
    {
        return view('pages.staff');
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

}
// php artisan make:controller HomeController