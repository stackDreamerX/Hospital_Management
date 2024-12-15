<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AppointmentController extends Controller
{
    private $sampleAppointments = [
        [
            'AppointmentID' => 1,
            'PatientID' => 1,
            'PatientName' => 'John Doe',
            'DoctorID' => 1,
            'AppointmentDate' => '2024-03-20',
            'AppointmentTime' => '09:00',
            'Status' => 'Pending',
            'Reason' => 'Regular checkup',
            'Notes' => null,
            'PatientPhone' => '0123456789',
            'PatientEmail' => 'john.doe@example.com'
        ],
        [
            'AppointmentID' => 2,
            'PatientID' => 2,
            'PatientName' => 'Jane Smith',
            'DoctorID' => 1,
            'AppointmentDate' => '2024-03-20',
            'AppointmentTime' => '10:30',
            'Status' => 'Approved',
            'Reason' => 'Follow-up visit',
            'Notes' => 'Patient has previous medical history',
            'PatientPhone' => '0123456788',
            'PatientEmail' => 'jane.smith@example.com'
        ],
        [
            'AppointmentID' => 3,
            'PatientID' => 3,
            'PatientName' => 'Robert Johnson',
            'DoctorID' => 1,
            'AppointmentDate' => '2024-03-21',
            'AppointmentTime' => '14:00',
            'Status' => 'Rejected',
            'Reason' => 'Consultation',
            'Notes' => 'Doctor unavailable on this date',
            'PatientPhone' => '0123456787',
            'PatientEmail' => 'robert.j@example.com'
        ]
    ];

    public function index()
    {
        $doctorId = session('doctor_id', 1); // Get logged in doctor's ID
        
        $appointments = collect($this->sampleAppointments)
            ->where('DoctorID', $doctorId)
            ->sortBy('AppointmentDate');

        $pendingCount = $appointments->where('Status', 'Pending')->count();
        $todayCount = $appointments
            ->where('AppointmentDate', date('Y-m-d'))
            ->count();

        return view('doctor.appointments', compact('appointments', 'pendingCount', 'todayCount'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Approved,Rejected',
            'notes' => 'nullable|string'
        ]);

        // In real application, update database here
        return response()->json([
            'message' => 'Appointment status updated successfully'
        ]);
    }
} 