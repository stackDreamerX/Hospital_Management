<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LabController extends Controller
{
    private $sampleLabTests = [
        [
            'LaboratoryID' => 1,
            'LaboratoryTypeName' => 'Blood Test',
            'DoctorName' => 'Dr. Sarah Wilson',
            'LaboratoryDate' => '2024-03-20',
            'LaboratoryTime' => '09:00',
            'Status' => 'Completed',
            'Result' => 'Normal blood count levels',
            'TotalPrice' => 2500,
            'PaymentStatus' => 'Paid',
            'Report' => 'path/to/report.pdf'
        ],
        [
            'LaboratoryID' => 2,
            'LaboratoryTypeName' => 'X-Ray',
            'DoctorName' => 'Dr. Michael Brown',
            'LaboratoryDate' => '2024-03-25',
            'LaboratoryTime' => '14:30',
            'Status' => 'Pending',
            'Result' => null,
            'TotalPrice' => 3500,
            'PaymentStatus' => 'Pending',
            'Report' => null
        ]
    ];

    public function index()
    {
        $patientId = session('patient_id', 1);
        
        $labTests = collect($this->sampleLabTests)
            ->sortByDesc('LaboratoryDate');

        $pendingTests = $labTests->where('Status', 'Pending')->count();
        $completedTests = $labTests->where('Status', 'Completed')->count();

        return view('patient.lab', compact(
            'labTests',
            'pendingTests',
            'completedTests'
        ));
    }

    public function downloadReport($id)
    {
        // In real application, validate and download report
        return response()->json(['message' => 'Report downloaded successfully']);
    }
} 