<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LabController extends Controller
{
    private $sampleLabTypes = [
        [
            'LaboratoryTypeID' => 1, 
            'LaboratoryTypeName' => 'Blood Test',
            'Price' => 2500
        ],
        [
            'LaboratoryTypeID' => 2, 
            'LaboratoryTypeName' => 'Urine Test',
            'Price' => 1500
        ],
        [
            'LaboratoryTypeID' => 3, 
            'LaboratoryTypeName' => 'X-Ray',
            'Price' => 3500
        ],
        [
            'LaboratoryTypeID' => 4, 
            'LaboratoryTypeName' => 'MRI Scan',
            'Price' => 15000
        ],
        [
            'LaboratoryTypeID' => 5, 
            'LaboratoryTypeName' => 'CT Scan',
            'Price' => 12000
        ],
    ];

    private $sampleLaboratories = [
        [
            'LaboratoryID' => 1,
            'LaboratoryTypeID' => 1,
            'LaboratoryTypeName' => 'Blood Test',
            'PatientID' => 1,
            'PatientName' => 'John Doe',
            'DoctorID' => 1,
            'DoctorName' => 'Dr. Sarah Wilson',
            'LaboratoryDate' => '2024-03-20',
            'LaboratoryTime' => '09:00',
            'TotalPrice' => 2500,
            'Status' => 'Pending',
            'Result' => null
        ],
        [
            'LaboratoryID' => 2,
            'LaboratoryTypeID' => 2,
            'LaboratoryTypeName' => 'Urine Test',
            'PatientID' => 2,
            'PatientName' => 'Jane Smith',
            'DoctorID' => 1,
            'DoctorName' => 'Dr. Sarah Wilson',
            'LaboratoryDate' => '2024-03-21',
            'LaboratoryTime' => '10:30',
            'TotalPrice' => 1500,
            'Status' => 'Completed',
            'Result' => 'Normal'
        ]
    ];

    private $samplePatients = [
        ['PatientID' => 1, 'FullName' => 'John Doe'],
        ['PatientID' => 2, 'FullName' => 'Jane Smith'],
        ['PatientID' => 3, 'FullName' => 'Robert Johnson']
    ];

    public function lab()
    {
        $doctorId = session('doctor_id', 1);
        
        $labTypes = collect($this->sampleLabTypes);
        $patients = collect($this->samplePatients);
        $laboratories = collect($this->sampleLaboratories)
            ->where('DoctorID', $doctorId)
            ->sortByDesc('LaboratoryDate');

        return view('doctor.lab', compact(
            'labTypes',
            'patients',
            'laboratories'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lab_type' => 'required|integer',
            'patient_id' => 'required|integer',
            'lab_date' => 'required|date|after_or_equal:today',
            'lab_time' => 'required'
        ]);

        // In real application, save to database
        return response()->json(['message' => 'Lab test assigned successfully']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Completed',
            'result' => 'required|string'
        ]);

        // In real application, update database
        return response()->json(['message' => 'Lab test updated successfully']);
    }
} 