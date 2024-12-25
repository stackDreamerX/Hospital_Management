<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $patientId = Auth::user()->UserID; // Lấy ID từ session
        $appointments = Appointment::with('doctor')
            ->where('UserID', $patientId)
            ->orderByDesc('AppointmentDate')
            ->get();
        $doctors = Doctor::with('user')->get();
        $pendingCount = $appointments->where('Status', 'Pending')->count();
        $approvedCount = $appointments->where('Status', 'Approved')->count();

        return view('patient.appointments', compact('doctors','appointments', 'pendingCount', 'approvedCount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required',
            'reason' => 'required|string|max:255',
            'symptoms' => 'required|string',
            'notes' => 'nullable|string',
            'doctor_id' => 'required|exists:doctors,DoctorID' // Validate DoctorID
        ]);

        Appointment::create([
            'AppointmentDate' => $request->appointment_date,
            'AppointmentTime' => $request->appointment_time,
            'Reason' => $request->reason,
            'Symptoms' => $request->symptoms,
            'Notes' => $request->notes,
            'DoctorID' => $request->doctor_id, // Lưu DoctorID
            'UserID' => Auth::user()->UserID, // Lấy UserID từ Auth
            'Status' => 'Pending'
        ]);

        return response()->json(['message' => 'Đặt lịch thành công!']);
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        if ($appointment->Status != 'Pending') {
            return response()->json(['error' => 'Không thể sửa cuộc hẹn này!'], 403);
        }

        $request->validate([
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required',
            'reason' => 'required|string|max:255',
            'symptoms' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $appointment->update($request->all());
        return response()->json(['message' => 'Cập nhật cuộc hẹn thành công!']);
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        if ($appointment->Status != 'Pending') {
            return response()->json(['error' => 'Không thể hủy cuộc hẹn này!'], 403);
        }

        $appointment->delete();
        return response()->json(['message' => 'Hủy cuộc hẹn thành công!']);
    }

    public function show($id)
    {
        $appointment = Appointment::where('AppointmentID', $id)
            ->where('UserID', Auth::user()->UserID)
            ->firstOrFail();

        return response()->json($appointment);
    }

}