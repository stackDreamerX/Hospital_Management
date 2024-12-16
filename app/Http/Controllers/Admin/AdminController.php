<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
session_start();

class AdminController extends Controller
{
    private $sampleDoctors = [
        ['DoctorID' => 1, 'FullName' => 'Dr. Sarah Wilson', 'Status' => 'online'],
        ['DoctorID' => 2, 'FullName' => 'Dr. Michael Brown', 'Status' => 'online'],
        ['DoctorID' => 3, 'FullName' => 'Dr. Emily Davis', 'Status' => 'offline'],
        ['DoctorID' => 4, 'FullName' => 'Dr. Jessica Taylor', 'Status' => 'online'],
        ['DoctorID' => 5, 'FullName' => 'Dr. James Anderson', 'Status' => 'offline']
    ];

    private $samplePatients = [
        ['PatientID' => 1, 'FullName' => 'John Doe'],
        ['PatientID' => 2, 'FullName' => 'Jane Smith'],
        ['PatientID' => 3, 'FullName' => 'Robert Johnson'],
        ['PatientID' => 4, 'FullName' => 'Mary Williams'],
        ['PatientID' => 5, 'FullName' => 'David Brown']
    ];

    private $sampleAppointments = [
        [
            'AppointmentID' => 1,
            'PatientName' => 'John Doe',
            'DoctorName' => 'Dr. Sarah Wilson',
            'AppointmentDate' => '2024-03-20',
            'AppointmentTime' => '09:00',
            'Status' => 'pending'
        ],
        [
            'AppointmentID' => 2,
            'PatientName' => 'Jane Smith',
            'DoctorName' => 'Dr. Michael Brown',
            'AppointmentDate' => '2024-03-20',
            'AppointmentTime' => '10:30',
            'Status' => 'approved'
        ],
        [
            'AppointmentID' => 3,
            'PatientName' => 'Robert Johnson',
            'DoctorName' => 'Dr. Emily Davis',
            'AppointmentDate' => '2024-03-21',
            'AppointmentTime' => '14:00',
            'Status' => 'completed'
        ]
    ];

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
        return view('admin_login');
    }

    public function show_dashboard() {
        

        // Calculate available beds
        $totalBeds = collect($this->sampleWards)->sum('Capacity');
        $occupiedBeds = collect($this->sampleWards)->sum('CurrentOccupancy');
        $availableBeds = $totalBeds - $occupiedBeds;

        // Get today's appointments
        $todayAppointments = collect($this->sampleAppointments)
            ->where('AppointmentDate', date('Y-m-d'))
            ->count();

        // Get recent appointments
        $recentAppointments = collect($this->sampleAppointments)
            ->sortByDesc('AppointmentDate')
            ->take(5);

        return view('admin.dashboard', [
            'doctors' => $this->sampleDoctors,
            'patients' => $this->samplePatients,
            'recentAppointments' => $recentAppointments,
            'wards' => $this->sampleWards,
            'todayAppointments' => $todayAppointments,
            'availableBeds' => $availableBeds
        ]);
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
            return Redirect::to('/dashboard');
        } else {
            Session::put('message', 'Mật khẩu hoặc tài khoản sai. vui lòng nhập lại');
            return Redirect::to('/admin');
        }
    }

    public function logout() {
        Session::put('admin_name', null);
        Session::put('admin_id', null);
        return Redirect::to('/admin'); 
    }
}
