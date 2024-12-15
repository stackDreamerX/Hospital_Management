<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PatientController extends Controller
{
    private $samplePatients = [
        [
            'PatientID' => 1,
            'FullName' => 'John Doe',
            'Age' => 35,
            'Gender' => 'Male',
            'Phone' => '0123456789',
            'Email' => 'john.doe@example.com',
            'LastVisit' => '2024-03-15',
            'Appointments' => [
                [
                    'Date' => '2024-03-15',
                    'Type' => 'Consultation',
                    'Status' => 'Completed'
                ],
                [
                    'Date' => '2024-03-20',
                    'Type' => 'Follow-up',
                    'Status' => 'Pending'
                ]
            ],
            'LabTests' => [
                [
                    'Date' => '2024-03-15',
                    'Type' => 'Blood Test',
                    'Result' => 'Normal'
                ]
            ],
            'Prescriptions' => [
                [
                    'Date' => '2024-03-15',
                    'Medicines' => ['Paracetamol', 'Vitamin C'],
                    'Status' => 'Dispensed'
                ]
            ],
            'Treatments' => [
                [
                    'Date' => '2024-03-15',
                    'Type' => 'Physical Therapy',
                    'Status' => 'Ongoing'
                ]
            ]
        ],
        // Add more sample patients...
    ];

    public function index()
    {
        $doctorId = session('doctor_id', 1); // Get logged in doctor's ID
        
        // Get all appointments, labs, prescriptions, and treatments for this doctor
        $appointments = collect($this->getAppointments($doctorId));
        $labTests = collect($this->getLabTests($doctorId));
        $prescriptions = collect($this->getPrescriptions($doctorId));
        $treatments = collect($this->getTreatments($doctorId));

        // Get unique patient IDs from all services
        $patientIds = collect()
            ->merge($appointments->pluck('PatientID'))
            ->merge($labTests->pluck('PatientID'))
            ->merge($prescriptions->pluck('PatientID'))
            ->merge($treatments->pluck('PatientID'))
            ->unique();

        // Filter patients who have used any of the doctor's services
        $patients = collect($this->samplePatients)
            ->whereIn('PatientID', $patientIds)
            ->values();

        return view('doctor.patients', compact('patients'));
    }

    public function show($id)
    {
        $doctorId = session('doctor_id', 1);
        
        // Get patient details
        $patient = collect($this->samplePatients)
            ->firstWhere('PatientID', $id);

        if (!$patient) {
            return redirect()->back()->with('error', 'Patient not found');
        }

        // Get all services for this patient with this doctor
        $appointments = collect($this->getAppointments($doctorId))
            ->where('PatientID', $id)
            ->sortByDesc('Date');

        $labTests = collect($this->getLabTests($doctorId))
            ->where('PatientID', $id)
            ->sortByDesc('Date');

        $prescriptions = collect($this->getPrescriptions($doctorId))
            ->where('PatientID', $id)
            ->sortByDesc('Date');

        $treatments = collect($this->getTreatments($doctorId))
            ->where('PatientID', $id)
            ->sortByDesc('Date');

        return view('doctor.patient-details', compact(
            'patient',
            'appointments',
            'labTests',
            'prescriptions',
            'treatments'
        ));
    }

    private function getAppointments($doctorId)
    {
        // Sample appointments data
        return [
            [
                'PatientID' => 1,
                'Date' => '2024-03-20',
                'Type' => 'Follow-up',
                'Status' => 'Pending'
            ]
        ];
    }

    private function getLabTests($doctorId)
    {
        // Sample lab tests data
        return [
            [
                'PatientID' => 1,
                'Date' => '2024-03-15',
                'Type' => 'Blood Test',
                'Result' => 'Normal'
            ]
        ];
    }

    private function getPrescriptions($doctorId)
    {
        // Sample prescriptions data
        return [
            [
                'PatientID' => 1,
                'Date' => '2024-03-15',
                'Medicines' => ['Paracetamol', 'Vitamin C'],
                'Status' => 'Dispensed'
            ]
        ];
    }

    private function getTreatments($doctorId)
    {
        // Sample treatments data
        return [
            [
                'PatientID' => 1,
                'Date' => '2024-03-15',
                'Type' => 'Physical Therapy',
                'Status' => 'Ongoing'
            ]
        ];
    }
} 