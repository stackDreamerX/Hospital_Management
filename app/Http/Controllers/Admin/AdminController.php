<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
session_start();
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Ward;

class AdminController extends Controller
{
   
    private $sampleWards = [
        [
            'WardID' => 1,
            'WardName' => 'General Ward',
            'Capacity' => 30,
            'CurrentOccupancy' => 25
        ],
        [
            'WardID' => 2,
            'WardName' => 'ICU',
            'Capacity' => 10,
            'CurrentOccupancy' => 8
        ],
        [
            'WardID' => 3,
            'WardName' => 'Pediatric Ward',
            'Capacity' => 20,
            'CurrentOccupancy' => 15
        ],
        [
            'WardID' => 4,
            'WardName' => 'Maternity Ward',
            'Capacity' => 15,
            'CurrentOccupancy' => 12
        ]
    ];

    public function index() {
        return view('pages.sign_in');
    }

  
    public function show_dashboard()
    {
        // Lấy tất cả cuộc hẹn
        $appointments = Appointment::with(['user', 'doctor.user'])
            ->orderByDesc('AppointmentDate')
            ->get();

        // Cập nhật trạng thái 'Overdue' cho các cuộc hẹn quá hạn
        foreach ($appointments as $appointment) {
            if ($appointment->AppointmentDate < date('Y-m-d') && $appointment->Status === 'Pending') {
                $appointment->update(['Status' => 'Overdue']);
            }
        }      

        $wards = $this->sampleWards; 
        // Thống kê dữ liệu
        $recentAppointments = $appointments;   //->take(10);
        $todayAppointments = $appointments->where('AppointmentDate', date('Y-m-d'))->count();
        $doctors = Doctor::all();
        $patients = User::where('roleID', 'patient')->get();
        $availableBeds = 0 ;  //Ward::sum('AvailableBeds');

        // Truyền dữ liệu sang view
        return view('admin.dashboard', compact(
            'recentAppointments', 
            'todayAppointments', 
            'doctors', 
            'patients', 
            'availableBeds',
            'wards'
        ));
    }



    public function dashboard(Request $request) {
        $admin_email = $request->admin_email;
        $admin_password = md5($request->admin_password);

        $result = DB::table('tbl_admin')
            ->where('admin_email', $admin_email)
            ->where('admin_password', $admin_password)
            ->first();

        if ($result) {
            Session::put('admin_name', $result->admin_name);
            Session::put('admin_id', $result->admin_id);
            return Redirect() -> route('show_dashboard');
        } else {
            Session::put('message', 'Mật khẩu hoặc tài khoản sai. vui lòng nhập lại');
            return Redirect() -> route('admin');
        }
    }

    public function logout() {
        Session::put('admin_name', null);
        Session::put('admin_id', null);
        return Redirect::to('/admin');
    }
}
