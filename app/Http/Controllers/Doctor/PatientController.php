<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index()
    {             
        $userId = Auth::id();

      
        $doctor = \App\Models\Doctor::where('UserID', $userId)->first();

        if (!$doctor) {
            return redirect()->back()->with('error', 'Doctor information not found.');
        }

        $doctorId = $doctor->DoctorID; 
   
        $userIds = \App\Models\Appointment::where('DoctorID', $doctorId)
        ->pluck('UserID')
        ->unique();

    
        $patients = \App\Models\User::whereIn('UserID', $userIds)
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

      
        $doctor = \App\Models\Doctor::where('UserID', $userId)->first();
        if (!$doctor) {
            return redirect()->back()->with('error', 'Doctor information not found.');
        }
        $doctorId = $doctor->DoctorID;

     
        $patient = \App\Models\User::where('RoleID', 'patient')->findOrFail($id);

     
        $appointments = \App\Models\Appointment::where('UserID', $id)
            ->where('DoctorID', $doctorId)
            ->orderByDesc('AppointmentDate')
            ->get();

      
        $treatments = \App\Models\Treatment::where('UserID', $id)
            ->where('DoctorID', $doctorId)
            ->orderByDesc('TreatmentDate')
            ->get();

      
        $prescriptions = \App\Models\Prescription::with(['prescriptionDetails.medicine'])
            ->where('UserID', $id)
            ->where('DoctorID', $doctorId)
            ->orderByDesc('PrescriptionDate')
            ->get();

     
        $labTests = \App\Models\Laboratory::with(['laboratoryResult', 'laboratoryDetail', 'laboratoryType'])
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
