<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{

    public function index()
    {
        $userID = auth()->user()->UserID; // Lấy ID bác sĩ đã đăng nhập từ Auth   
        $doctorId = Doctor::where('UserID', $userID)->pluck('DoctorID')->first();

        $appointments = Appointment::with('user')
            ->where('DoctorID', $doctorId)
            ->orderBy('AppointmentDate', 'asc')
            ->get();

        $pendingCount = $appointments->where('Status', 'pending')->count();
        $todayCount = $appointments->where('AppointmentDate', date('Y-m-d'))->count();
        
        return view('doctor.appointments', compact('appointments', 'pendingCount', 'todayCount'));
    }


    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $status = $request->input('status');
        $notes = $request->input('notes', null);

        $appointment->update([
            'Status' => $status,
            'DoctorNotes' => $notes,
        ]);

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

} 