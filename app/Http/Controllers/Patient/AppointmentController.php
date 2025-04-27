<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Doctor;
use App\Mail\AppointmentCreated;
use App\Mail\AppointmentUpdated;
use App\Mail\AppointmentCancelled;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $patientId = Auth::check() ? Auth::user()->UserID : null; // Lấy ID từ session
        $appointments = Appointment::with('doctor')
            ->where('UserID', $patientId)
            ->orderByDesc('AppointmentDate')
            ->get();
        $doctors = Doctor::with('user')->get();
        $pendingCount = $appointments->where('Status', 'pending')->count();
        $approvedCount = $appointments->where('Status', 'approved')->count();
        return view('patient.appointments', compact('doctors','appointments', 'pendingCount', 'approvedCount'));
    }

    public function store(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'User is not logged in'], 403);
            }
            $validated = $request->validate([
                'appointment_date' => 'required|date|after:today',
                'appointment_time' => 'required',
                'reason' => 'required|string|max:255',
                'symptoms' => 'required|string',
                'notes' => 'nullable|string',
                'doctor_id' => 'required|exists:doctors,DoctorID',
            ]);
            // \Log::info('Auth User:', [Auth::user()]);

            $appointment = Appointment::create([
                'AppointmentDate' => $validated['appointment_date'],
                'AppointmentTime' => $validated['appointment_time'],
                'Reason' => $validated['reason'],
                'Symptoms' => $validated['symptoms'],
                'Notes' => $validated['notes'],
                'UserID' => Auth::check() ? Auth::user()->UserID : null,
                'DoctorID' => $validated['doctor_id'],
                'Status' => 'pending',
            ]);

            // Load the relationships for email
            $appointment->load(['user', 'doctor.user']);

            // Send email notification
            try {
                Mail::to($appointment->user->Email)->send(new AppointmentCreated($appointment));
                Log::info('Appointment creation email sent successfully to ' . $appointment->user->Email);
            } catch (\Exception $e) {
                Log::error('Error sending appointment creation email: ' . $e->getMessage());
            }

            return response()->json(['message' => 'Appointment created successfully']);
        } catch (\Exception $e) {
            Log::error('Error creating appointment: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create appointment.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        if ($appointment->Status != 'pending') {
            return response()->json(['error' => 'Không thể sửa cuộc hẹn này!'], 403);
        }

        // Xác thực dữ liệu
        $validated = $request->validate([
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required',
            'reason' => 'required|string|max:255',
            'symptoms' => 'required|string',
            'notes' => 'nullable|string',
            'doctor_id' => 'required|exists:doctors,DoctorID',
        ]);
        
        try {
            // Cập nhật dữ liệu
            $appointment->update([
                'AppointmentDate' => $validated['appointment_date'],
                'AppointmentTime' => $validated['appointment_time'],
                'Reason' => $validated['reason'],
                'Symptoms' => $validated['symptoms'],
                'Notes' => $validated['notes'],
                'DoctorID' => $validated['doctor_id'],
            ]);

            // Load the relationships for email
            $appointment->load(['user', 'doctor.user']);

            // Send email notification
            try {
                Mail::to($appointment->user->Email)->send(new AppointmentUpdated($appointment));
                Log::info('Appointment update email sent successfully to ' . $appointment->user->Email);
            } catch (\Exception $e) {
                Log::error('Error sending appointment update email: ' . $e->getMessage());
            }

            return response()->json(['message' => 'Cập nhật cuộc hẹn thành công!']);
        } catch (\Exception $e) {
            Log::error('Error updating appointment: ' . $e->getMessage());
            return response()->json(['error' => 'Không thể cập nhật cuộc hẹn.'], 500);
        }
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        if ($appointment->Status != 'pending') {
            return response()->json(['error' => 'Không thể hủy cuộc hẹn này!'], 403);
        }

        try {
            // Load the relationships before deleting
            $appointment->load(['user', 'doctor.user']);
            
            // Store appointment data for email
            $appointmentData = $appointment->toArray();
            $appointmentData['user'] = $appointment->user->toArray();
            $appointmentData['doctor'] = $appointment->doctor ? $appointment->doctor->toArray() : null;
            $appointmentData['doctor']['user'] = $appointment->doctor && $appointment->doctor->user 
                ? $appointment->doctor->user->toArray() 
                : null;
            
            // Get user email before deletion
            $userEmail = $appointment->user->Email;
            
            // Create a new appointment instance with the stored data
            // This is needed because the original will be deleted
            $appointmentForEmail = new Appointment($appointmentData);
            $appointmentForEmail->user = new User($appointmentData['user']);
            if ($appointmentData['doctor']) {
                $doctorModel = new Doctor($appointmentData['doctor']);
                if ($appointmentData['doctor']['user']) {
                    $doctorModel->user = new User($appointmentData['doctor']['user']);
                }
                $appointmentForEmail->doctor = $doctorModel;
            }
            
            // Delete the appointment
            $appointment->delete();
            
            // Send email notification
            try {
                Mail::to($userEmail)->send(new AppointmentCancelled($appointmentForEmail));
                Log::info('Appointment cancellation email sent successfully to ' . $userEmail);
            } catch (\Exception $e) {
                Log::error('Error sending appointment cancellation email: ' . $e->getMessage());
            }

            return response()->json(['message' => 'Hủy cuộc hẹn thành công!']);
        } catch (\Exception $e) {
            Log::error('Error cancelling appointment: ' . $e->getMessage());
            return response()->json(['error' => 'Không thể hủy cuộc hẹn.'], 500);
        }
    }

    public function show($id)
    {
        $appointment = Appointment::where('AppointmentID', $id)
            ->where('UserID', Auth::user()->UserID)
            ->firstOrFail();

        return response()->json($appointment);
    }

    public function showDetail($id)
    {
        try {
            $appointment = Appointment::with('doctor.user')->findOrFail($id);

            return response()->json([
                'AppointmentDate' => $appointment->AppointmentDate,
                'AppointmentTime' => $appointment->AppointmentTime,
                'DoctorName' => $appointment->doctor->user->FullName ?? 'Chưa được chỉ định',
                'Status' => $appointment->Status,
                'Reason' => $appointment->Reason,
                'Symptoms' => $appointment->Symptoms,
                'Notes' => $appointment->Notes,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không tìm thấy thông tin cuộc hẹn.'], 404);
        }
    }
}