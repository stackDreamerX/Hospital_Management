<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LabController extends Controller
{
    private $sampleLabTypes = [
        [
            'LaboratoryTypeID' => 1,
            'LaboratoryTypeName' => 'Blood Test',
            'Description' => 'Complete blood count and analysis',
            'Price' => 2500,
            'Status' => 'Active'
        ],
        [
            'LaboratoryTypeID' => 2,
            'LaboratoryTypeName' => 'Urine Test',
            'Description' => 'Comprehensive urine analysis',
            'Price' => 1500,
            'Status' => 'Active'
        ],
        [
            'LaboratoryTypeID' => 3,
            'LaboratoryTypeName' => 'X-Ray',
            'Description' => 'Radiological examination',
            'Price' => 3500,
            'Status' => 'Active'
        ],
        [
            'LaboratoryTypeID' => 4,
            'LaboratoryTypeName' => 'MRI Scan',
            'Description' => 'Magnetic resonance imaging',
            'Price' => 15000,
            'Status' => 'Active'
        ],
        [
            'LaboratoryTypeID' => 5,
            'LaboratoryTypeName' => 'CT Scan',
            'Description' => 'Computerized tomography scan',
            'Price' => 12000,
            'Status' => 'Active'
        ]
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
            'Result' => null,
            'PaymentStatus' => 'Pending'
        ],
        [
            'LaboratoryID' => 2,
            'LaboratoryTypeID' => 2,
            'LaboratoryTypeName' => 'Urine Test',
            'PatientID' => 2,
            'PatientName' => 'Jane Smith',
            'DoctorID' => 2,
            'DoctorName' => 'Dr. Michael Brown',
            'LaboratoryDate' => '2024-03-21',
            'LaboratoryTime' => '10:30',
            'TotalPrice' => 1500,
            'Status' => 'Completed',
            'Result' => 'Normal',
            'PaymentStatus' => 'Paid'
        ]
    ];

    private $sampleDoctors = [
        ['DoctorID' => 1, 'FullName' => 'Dr. Sarah Wilson', 'Specialization' => 'General Medicine'],
        ['DoctorID' => 2, 'FullName' => 'Dr. Michael Brown', 'Specialization' => 'Cardiology'],
        ['DoctorID' => 3, 'FullName' => 'Dr. Emily Davis', 'Specialization' => 'Pediatrics']
    ];

    private $samplePatients = [
        ['PatientID' => 1, 'FullName' => 'John Doe', 'Age' => 35],
        ['PatientID' => 2, 'FullName' => 'Jane Smith', 'Age' => 28],
        ['PatientID' => 3, 'FullName' => 'Robert Johnson', 'Age' => 45]
    ];

    public function lab()
    {
        $labTypes = collect($this->sampleLabTypes);
        $laboratories = collect($this->sampleLaboratories)
            ->sortByDesc('LaboratoryDate');
        $doctors = collect($this->sampleDoctors);
        $patients = collect($this->samplePatients);

        // Calculate statistics
        $totalTests = $laboratories->count();
        $pendingTests = $laboratories->where('Status', 'Pending')->count();
        $completedTests = $laboratories->where('Status', 'Completed')->count();
        $totalRevenue = $laboratories->where('PaymentStatus', 'Paid')->sum('TotalPrice');

        return view('admin.lab', compact(
            'labTypes',
            'laboratories',
            'doctors',
            'patients',
            'totalTests',
            'pendingTests',
            'completedTests',
            'totalRevenue'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lab_type' => 'required|integer',
            'patient_id' => 'required|integer',
            'doctor_id' => 'required|integer',
            'lab_date' => 'required|date|after_or_equal:today',
            'lab_time' => 'required',
            'price' => 'required|numeric|min:0'
        ]);

        // In real application, save to database
        return response()->json(['message' => 'Lab test created successfully']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'lab_type' => 'required|integer',
            'patient_id' => 'required|integer',
            'doctor_id' => 'required|integer',
            'lab_date' => 'required|date',
            'lab_time' => 'required',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:Pending,Completed,Cancelled',
            'payment_status' => 'required|in:Pending,Paid,Refunded'
        ]);

        // In real application, update database
        return response()->json(['message' => 'Lab test updated successfully']);
    }

    public function destroy($id)
    {
        // In real application, delete from database
        return response()->json(['message' => 'Lab test deleted successfully']);
    }

    public function updateLabType(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:Active,Inactive'
        ]);

        // In real application, update database
        return response()->json(['message' => 'Lab type updated successfully']);
    }

    public function storeLabType(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0'
        ]);

        // In real application, save to database
        return response()->json(['message' => 'Lab type created successfully']);
    }

    public function deleteLabType($id)
    {
        // In real application, delete from database
        return response()->json(['message' => 'Lab type deleted successfully']);
    }

    public function generateReport(Request $request)
    {
        // In real application, generate PDF report
        return response()->json(['message' => 'Report generated successfully']);
    }
}
