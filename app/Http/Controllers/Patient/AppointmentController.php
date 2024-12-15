<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AppointmentController extends Controller
{
    private $sampleAppointments = [
        [
            'AppointmentID' => 1,
            'PatientID' => 1,
            'DoctorID' => null,  // Not assigned yet
            'DoctorName' => null,
            'AppointmentDate' => '2024-03-25',
            'AppointmentTime' => '09:00',
            'Status' => 'Pending',
            'Reason' => 'Regular checkup',
            'Symptoms' => 'Headache, fever',
            'Notes' => null,
            'AdminNotes' => null
        ],
        [
            'AppointmentID' => 2,
            'PatientID' => 1,
            'DoctorID' => 1,
            'DoctorName' => 'Dr. Sarah Wilson',
            'AppointmentDate' => '2024-03-20',
            'AppointmentTime' => '14:30',
            'Status' => 'Approved',
            'Reason' => 'Follow-up',
            'Symptoms' => 'None',
            'Notes' => 'Previous treatment follow-up',
            'AdminNotes' => 'Assigned to Dr. Wilson based on previous consultation'
        ]
    ];

    public function index()
    {
        $patientId = session('patient_id', 1);
        
        $appointments = collect($this->sampleAppointments)
            ->where('PatientID', $patientId)
            ->sortByDesc('AppointmentDate');

        $pendingCount = $appointments->where('Status', 'Pending')->count();
        $approvedCount = $appointments->where('Status', 'Approved')->count();

        return view('patient.appointments', compact(
            'appointments',
            'pendingCount',
            'approvedCount'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required',
            'reason' => 'required|string|max:255',
            'symptoms' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        // In real application, save to database
        return response()->json(['message' => 'Appointment request submitted successfully']);
    }

    public function update(Request $request, $id)
    {
        // Only allow updates if appointment is pending
        $appointment = collect($this->sampleAppointments)
            ->firstWhere('AppointmentID', $id);

        if (!$appointment || $appointment['Status'] !== 'Pending') {
            return response()->json(['error' => 'Cannot modify this appointment'], 403);
        }

        $request->validate([
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required',
            'reason' => 'required|string|max:255',
            'symptoms' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        // In real application, update database
        return response()->json(['message' => 'Appointment updated successfully']);
    }

    public function destroy($id)
    {
        // Only allow deletion if appointment is pending
        $appointment = collect($this->sampleAppointments)
            ->firstWhere('AppointmentID', $id);

        if (!$appointment || $appointment['Status'] !== 'Pending') {
            return response()->json(['error' => 'Cannot cancel this appointment'], 403);
        }

        // In real application, delete from database
        return response()->json(['message' => 'Appointment cancelled successfully']);
    }
} 