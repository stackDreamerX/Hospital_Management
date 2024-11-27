<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
session_start();
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash; 

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
  
   public function home_dashboard(Request $request) {
       $email = $request->email;
       $password = md5($request->password);

       $result = DB::table('users') ->  where('email',$email) -> where('password', $password) -> first();       
       if ($result){
           Session::put('name',$result -> name);
           Session::put('id',$result -> id);
           return Redirect ::to('/trang-chu');
       }        
       else {
           Session::put('message','Mật khẩu hoặc tài khoản sai. vui lòng nhập lại');
           return Redirect::to('/sign-in');
       }
   }
   public function home_logout() {
       Session::put('name',null);
       Session::put('id',null);
       return Redirect::to('/trang-chu'); 
   }

   public function register(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Tạo user mới
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Chuyển hướng sau khi đăng ký thành công
        return redirect('/sign-in')->with('success', 'Account created successfully! Please log in.');
    }


}
// php artisan make:controller HomeController 