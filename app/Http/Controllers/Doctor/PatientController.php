<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index()
    {
        \DB::enableQueryLog();
        // Lấy UserID của bác sĩ đang đăng nhập
        $userId = Auth::id();

        // Lấy DoctorID dựa trên UserID từ bảng doctors
        $doctor = \App\Models\Doctor::where('UserID', $userId)->first();

        if (!$doctor) {
            return redirect()->back()->with('error', 'Doctor information not found.');
        }

        $doctorId = $doctor->DoctorID; // Lấy DoctorID từ bảng doctors
   
        $userIds = \App\Models\Appointment::where('DoctorID', $doctorId)
        ->pluck('UserID')
        ->unique();

        // Lấy danh sách bệnh nhân mà bác sĩ đã làm việc thông qua appointments
        $patients = User::whereIn('UserID', $userIds) // Chỉ lấy bệnh nhân
            ->whereHas('appointments', function ($query) use ($doctorId) {
                $query->where('DoctorID', $doctorId);
            })
            ->with([
                'appointments' => function ($query) use ($doctorId) {
                    $query->where('DoctorID', $doctorId);
                },
                'laboratories' => function ($query) use ($doctorId) {
                    $query->where('DoctorID', $doctorId);
                },
                'prescriptions' => function ($query) use ($doctorId) {
                    $query->where('DoctorID', $doctorId);
                },
                'treatments' => function ($query) use ($doctorId) {
                    $query->where('DoctorID', $doctorId);
                },
            ])
            ->get()
            ->map(function ($patient) use ($doctorId) {
                $patient->appointment_count = $patient->appointments->where('DoctorID', $doctorId)->count();
                $patient->lab_test_count = $patient->laboratories->where('DoctorID', $doctorId)->count();
                $patient->prescription_count = $patient->prescriptions->where('DoctorID', $doctorId)->count();
                $patient->treatment_count = $patient->treatments->where('DoctorID', $doctorId)->count();
                return $patient;
            });

         
        return view('doctor.patients', compact('patients'));
    }

    

    public function show($id)
    {
        $userId = Auth::id();

        // Lấy DoctorID từ bảng doctors
        $doctor = \App\Models\Doctor::where('UserID', $userId)->first();
        if (!$doctor) {
            return redirect()->back()->with('error', 'Doctor information not found.');
        }
        $doctorId = $doctor->DoctorID;

        // Lấy thông tin bệnh nhân
        $patient = \App\Models\User::where('RoleID', 'patient')->findOrFail($id);

        // Lấy danh sách appointments liên quan đến bác sĩ hiện tại
        $appointments = \App\Models\Appointment::where('UserID', $id)
            ->where('DoctorID', $doctorId)
            ->orderByDesc('AppointmentDate')
            ->get();

        // Lấy danh sách treatments liên quan đến bác sĩ hiện tại
        $treatments = \App\Models\Treatment::where('UserID', $id)
            ->where('DoctorID', $doctorId)
            ->orderByDesc('TreatmentDate')
            ->get();

        // Lấy danh sách prescriptions liên quan đến bác sĩ hiện tại
        $prescriptions = \App\Models\Prescription::with(['prescriptionDetails.medicine'])
            ->where('UserID', $id)
            ->where('DoctorID', $doctorId)
            ->orderByDesc('PrescriptionDate')
            ->get();

        // Lấy danh sách lab tests liên quan đến bác sĩ hiện tại
        $labTests = \App\Models\Laboratory::with(['laboratoryResults', 'laboratoryDetails', 'laboratoryType'])
            ->where('DoctorID', $doctorId)
            ->where('UserID', $id)
            ->orderByDesc('LaboratoryDate')
            ->get();
                    
        

        return view('doctor.patient-details', compact(
            'patient',
            'appointments',
            'treatments',
            'prescriptions',
            'labTests'
        ));
    }


}
