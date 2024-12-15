<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TreatmentController extends Controller
{
    private $sampleTreatments = [
        [
            'TreatmentID' => 1,
            'PatientID' => 1,
            'PatientName' => 'John Doe',
            'DoctorID' => 1,
            'TreatmentDate' => '2024-03-20',
            'Type' => 'Physical Therapy',
            'Description' => 'Lower back rehabilitation',
            'Duration' => '45 minutes',
            'Status' => 'Scheduled',
            'Notes' => null,
            'Progress' => null
        ],
        [
            'TreatmentID' => 2,
            'PatientID' => 2,
            'PatientName' => 'Jane Smith',
            'DoctorID' => 1,
            'TreatmentDate' => '2024-03-19',
            'Type' => 'Wound Care',
            'Description' => 'Post-surgical wound dressing',
            'Duration' => '30 minutes',
            'Status' => 'Completed',
            'Notes' => 'Healing well, no signs of infection',
            'Progress' => 'Good progress, schedule follow-up in 1 week'
        ]
    ];

    private $treatmentTypes = [
        'Physical Therapy',
        'Wound Care',
        'Respiratory Therapy',
        'Speech Therapy',
        'Occupational Therapy',
        'Chemotherapy',
        'Radiation Therapy',
        'Dialysis'
    ];

    public function index()
    {
        $doctorId = session('doctor_id', 1);
        
        $treatments = collect($this->sampleTreatments)
            ->where('DoctorID', $doctorId)
            ->sortByDesc('TreatmentDate');

        $patients = collect($this->getMyPatients($doctorId));
        $treatmentTypes = collect($this->treatmentTypes);

        return view('doctor.treatment', compact(
            'treatments',
            'patients',
            'treatmentTypes'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|integer',
            'type' => 'required|string',
            'description' => 'required|string',
            'treatment_date' => 'required|date|after_or_equal:today',
            'duration' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        // In real application, save to database
        return response()->json(['message' => 'Treatment created successfully']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Completed,Cancelled',
            'progress' => 'required_if:status,Completed|nullable|string',
            'notes' => 'nullable|string'
        ]);

        // In real application, update database
        return response()->json(['message' => 'Treatment updated successfully']);
    }

    public function destroy($id)
    {
        // In real application, delete from database
        return response()->json(['message' => 'Treatment cancelled successfully']);
    }

    private function getMyPatients($doctorId)
    {
        // In real application, get from database
        return [
            ['PatientID' => 1, 'FullName' => 'John Doe'],
            ['PatientID' => 2, 'FullName' => 'Jane Smith']
        ];
    }
} 