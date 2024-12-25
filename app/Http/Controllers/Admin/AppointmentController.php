<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    private $sampleAppointments = [
        [
            'AppointmentID' => 1,
            'PatientID' => 1,
            'PatientName' => 'John Doe',
            'DoctorID' => 1,
            'DoctorName' => 'Dr. Sarah Wilson',
            'AppointmentDate' => '2024-03-20',
            'AppointmentTime' => '09:00',
            'Status' => 'Pending',
            'Reason' => 'Regular checkup',
            'Notes' => null
        ],
        [
            'AppointmentID' => 2,
            'PatientID' => 2,
            'PatientName' => 'Jane Smith',
            'DoctorID' => 2,
            'DoctorName' => 'Dr. Michael Brown',
            'AppointmentDate' => '2024-03-20',
            'AppointmentTime' => '10:30',
            'Status' => 'Approved',
            'Reason' => 'Follow-up',
            'Notes' => 'Previous treatment follow-up'
        ],
        [
            'AppointmentID' => 3,
            'PatientID' => 3,
            'PatientName' => 'Robert Johnson',
            'DoctorID' => 3,
            'DoctorName' => 'Dr. Emily Davis',
            'AppointmentDate' => '2024-03-21',
            'AppointmentTime' => '14:00',
            'Status' => 'Completed',
            'Reason' => 'Consultation',
            'Notes' => 'Regular health checkup'
        ]
    ];

    private $sampleDoctors = [
        [
            'DoctorID' => 1,
            'FullName' => 'Dr. Sarah Wilson',
            'Specialization' => 'General Medicine',
            'Status' => 'Available'
        ],
        [
            'DoctorID' => 2,
            'FullName' => 'Dr. Michael Brown',
            'Specialization' => 'Cardiology',
            'Status' => 'Available'
        ],
        [
            'DoctorID' => 3,
            'FullName' => 'Dr. Emily Davis',
            'Specialization' => 'Pediatrics',
            'Status' => 'Available'
        ]
    ];

    private $samplePatients = [
        [
            'PatientID' => 1,
            'FullName' => 'John Doe',
            'Age' => 35,
            'Phone' => '0123456789',
            'Email' => 'john.doe@example.com'
        ],
        [
            'PatientID' => 2,
            'FullName' => 'Jane Smith',
            'Age' => 28,
            'Phone' => '0123456788',
            'Email' => 'jane.smith@example.com'
        ],
        [
            'PatientID' => 3,
            'FullName' => 'Robert Johnson',
            'Age' => 45,
            'Phone' => '0123456787',
            'Email' => 'robert.j@example.com'
        ]
    ];

    public function appointment()
    {
        $appointments = collect($this->sampleAppointments)
            ->sortByDesc('AppointmentDate');
        
        $doctors = collect($this->sampleDoctors);
        $patients = collect($this->samplePatients);

        // Calculate statistics
        $totalAppointments = $appointments->count();
        $pendingAppointments = $appointments->where('Status', 'Pending')->count();
        $approvedAppointments = $appointments->where('Status', 'Approved')->count();
        $completedAppointments = $appointments->where('Status', 'Completed')->count();

        // Get today's appointments
        $todayAppointments = $appointments->where('AppointmentDate', date('Y-m-d'))->count();

        return view('admin.appointment', compact(
            'appointments',
            'doctors',
            'patients',
            'totalAppointments',
            'pendingAppointments',
            'approvedAppointments',
            'completedAppointments',
            'todayAppointments'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|integer',
            'doctor_id' => 'required|integer',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        // In real application, save to database
        return response()->json(['message' => 'Appointment created successfully']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'patient_id' => 'required|integer',
            'doctor_id' => 'required|integer',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'status' => 'required|in:Pending,Approved,Completed,Cancelled',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        // In real application, update database
        return response()->json(['message' => 'Appointment updated successfully']);
    }

    public function destroy($id)
    {
        // In real application, delete from database
        return response()->json(['message' => 'Appointment deleted successfully']);
    }

    public function generateReport(Request $request)
    {
        // In real application, generate PDF report
        return response()->json(['message' => 'Report generated successfully']);
    }
}