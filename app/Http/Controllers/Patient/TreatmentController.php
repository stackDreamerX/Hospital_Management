<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TreatmentController extends Controller
{
    private $sampleTreatments = [
        [
            'TreatmentID' => 1,
            'PatientID' => 1,
            'DoctorName' => 'Dr. Sarah Wilson',
            'TreatmentName' => 'Physical Therapy',
            'StartDate' => '2024-03-15',
            'EndDate' => '2024-04-15',
            'Status' => 'Ongoing',
            'Description' => 'Lower back rehabilitation',
            'Progress' => 'Good progress, continuing exercises',
            'Cost' => 25000,
            'PaymentStatus' => 'Partially Paid',
            'Notes' => 'Three sessions per week',
            'LabTests' => [
                'Blood Test',
                'X-Ray'
            ],
            'Medications' => [
                'Pain relievers',
                'Anti-inflammatory drugs'
            ]
        ],
        [
            'TreatmentID' => 2,
            'PatientID' => 1,
            'DoctorName' => 'Dr. Michael Brown',
            'TreatmentName' => 'Post-Surgery Care',
            'StartDate' => '2024-02-01',
            'EndDate' => '2024-03-01',
            'Status' => 'Completed',
            'Description' => 'Post-appendectomy care',
            'Progress' => 'Fully recovered',
            'Cost' => 15000,
            'PaymentStatus' => 'Paid',
            'Notes' => 'Regular wound dressing',
            'LabTests' => [
                'Blood Test'
            ],
            'Medications' => [
                'Antibiotics',
                'Pain relievers'
            ]
        ]
    ];

    public function index()
    {
        $patientId = session('patient_id', 1);
        
        $treatments = collect($this->sampleTreatments)
            ->where('PatientID', $patientId)
            ->sortByDesc('StartDate');

        $ongoingCount = $treatments->where('Status', 'Ongoing')->count();
        $completedCount = $treatments->where('Status', 'Completed')->count();
        $totalCost = $treatments->sum('Cost');
        $paidAmount = $treatments->where('PaymentStatus', 'Paid')->sum('Cost');

        return view('patient.treatment', compact(
            'treatments',
            'ongoingCount',
            'completedCount',
            'totalCost',
            'paidAmount'
        ));
    }

    public function show($id)
    {
        $treatment = collect($this->sampleTreatments)
            ->firstWhere('TreatmentID', $id);

        if (!$treatment) {
            return redirect()->back()->with('error', 'Treatment not found');
        }

        return view('patient.treatment-details', compact('treatment'));
    }

    public function downloadReport($id)
    {
        // In real application, generate and download PDF
        return response()->json(['message' => 'Treatment report downloaded successfully']);
    }
} 