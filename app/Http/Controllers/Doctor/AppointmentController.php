<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentStatusChanged;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{

    public function index()
    {
        $userID = auth()->user()->UserID; 
        $doctorId = Doctor::where('UserID', $userID)->pluck('DoctorID')->first();

        // Get appointments with eager loading of user relationship
        $appointments = Appointment::select('appointments.*', 'users.FullName', 'users.Email', 'users.PhoneNumber')
            ->join('users', 'users.UserID', '=', 'appointments.UserID')
            ->where('appointments.DoctorID', $doctorId)
            ->orderBy('appointments.AppointmentDate', 'asc')
            ->get();

        // Add user object to each appointment for blade template compatibility
        foreach ($appointments as $appointment) {
            $user = new \stdClass();
            $user->FullName = $appointment->FullName;
            $user->Email = $appointment->Email;
            $user->PhoneNumber = $appointment->PhoneNumber;
            $appointment->user = $user;
        }

        $pendingCount = $appointments->where('Status', 'pending')->count();
        $todayCount = $appointments->where('AppointmentDate', date('Y-m-d'))->count();
        
        return view('doctor.appointments', compact('appointments', 'pendingCount', 'todayCount'));
    }


    public function updateStatus(Request $request, $id)
    {
        // Get appointment
        $appointment = Appointment::findOrFail($id);
        $status = strtolower($request->input('status'));
        $notes = $request->input('notes', null);

        // Debug log
        Log::info('Updating appointment status', [
            'appointment_id' => $id,
            'old_status' => $appointment->Status,
            'new_status' => $status,
            'notes' => $notes
        ]);

        $appointment->update([
            'Status' => $status,
            'DoctorNotes' => $notes,
        ]);

        // Send email notification to the patient
        try {
            $patientUser = User::find($appointment->UserID);
            $doctorUser = null;
            
            if ($appointment->DoctorID) {
                $doctor = Doctor::find($appointment->DoctorID);
                if ($doctor) {
                    $doctorUser = User::find($doctor->UserID);
                }
            }
            
            // Set the doctor user on the appointment for the email template
            $appointment->doctorUser = $doctorUser;
            $appointment->patientUser = $patientUser;
            
            if ($patientUser && $patientUser->Email) {
                Mail::to($patientUser->Email)->send(new AppointmentStatusChanged($appointment));
                Log::info('Appointment status email sent to patient: ' . $patientUser->Email);
            } else {
                Log::warning('Could not send appointment status email - patient email not found');
            }
        } catch (\Exception $e) {
            Log::error('Failed to send appointment status email: ' . $e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

} 