<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class StaffController extends Controller
{
    private $sampleDoctors = [
        [
            'DoctorID' => 1,
            'UserID' => 1,
            'FullName' => 'Dr. Sarah Wilson',
            'Username' => 'dr.wilson',
            'Email' => 'sarah.wilson@example.com',
            'PhoneNumber' => '0123456789',
            'Speciality' => 'Cardiology',
            'Title' => 'Senior Consultant'
        ],
        [
            'DoctorID' => 2,
            'UserID' => 2,
            'FullName' => 'Dr. Michael Brown',
            'Username' => 'dr.brown',
            'Email' => 'michael.brown@example.com',
            'PhoneNumber' => '0123456788',
            'Speciality' => 'Neurology',
            'Title' => 'Specialist'
        ]
    ];

    // public function staff()
    // {
    //     $doctors = collect($this->sampleDoctors);
    //     return view('admin.staff', compact('doctors'));
    // }

    public function staff()
    {
        // Lấy danh sách các bác sĩ từ bảng doctors
        $doctors = Doctor::with('user')->get(); // Liên kết với bảng users
        return view('admin.staff', compact('doctors'));
    }

    public function createDoctor(Request $request)
    {
        // Xác thực đầu vào
        $validated = $request->validate([
            'username' => 'required|exists:users,username', // Username phải tồn tại trong bảng users
            'speciality' => 'required|string|max:100',
            'title' => 'required|string|max:100',
        ]);

        // Tìm user dựa trên username
        $user = User::where('username', $request->username)->first();

        if ($user->RoleID === 'doctor') {
            return back()->withErrors(['message' => 'Người dùng này đã là doctor.']);
        }

        // Cập nhật RoleID thành 'doctor'
        $user->RoleID = 'doctor';
        $user->save();

        // Thêm bác sĩ vào bảng doctors
        Doctor::create([
            'UserID' => $user->UserID,
            'Speciality' => $request->speciality,
            'Title' => $request->title,
        ]);

        return back()->with('message', 'Bác sĩ đã được thêm thành công!');
    }

    public function editDoctor(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->update([
            'Speciality' => $request->speciality,
            'Title' => $request->title,
        ]);

        $user = $doctor->user;
        $user->update([
            'FullName' => $request->fullname,
            'username' => $request->username,
            'Email' => $request->email,
            'PhoneNumber' => $request->phone,
        ]);

        return response()->json(['message' => 'Doctor updated successfully.']);
    }
    
    public function deleteDoctor($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->delete(); // Xóa khỏi bảng doctors

        $user = User::findOrFail($doctor->UserID);
        $user->update(['RoleID' => 'patient']); // Trả người dùng về RoleID 'patient'

        return response()->json(['message' => 'Doctor deleted successfully.']);
    }

//API
    public function getDoctorsList()
    {
        $doctors = Doctor::with('user')->get(); // Lấy danh sách bác sĩ với thông tin user      
        return response()->json($doctors);
    }


}
