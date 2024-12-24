<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use App\Models\Laboratory;
use App\Models\LaboratoryType;
use App\Models\Doctor;
use App\Models\Patient;

class LabController extends Controller
{
    
    public function lab()
    {
        $labTypes = LaboratoryType::all(); // Lấy danh sách loại xét nghiệm từ database
        $laboratories = Laboratory::with(['patient', 'doctor', 'laboratoryType'])
            ->orderBy('LaboratoryDate', 'desc')
            ->get(); // Lấy danh sách xét nghiệm kèm thông tin liên quan
        $doctors = Doctor::all(); // Danh sách bác sĩ
        $patients = Patient::all(); // Danh sách bệnh nhân

        // Tính toán thống kê
        $totalTests = $laboratories->count();
        $pendingTests = $laboratories->where('Status', 'Pending')->count();
        $completedTests = $laboratories->where('Status', 'Completed')->count();
        $totalRevenue = $laboratories->sum('TotalPrice');

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
            'lab_type' => 'required|exists:laboratory_types,LaboratoryTypeID',
            'patient_id' => 'required|exists:patients,PatientID',
            'doctor_id' => 'required|exists:doctors,DoctorID',
            'lab_date' => 'required|date|after_or_equal:today',
            'lab_time' => 'required',
            'price' => 'required|numeric|min:0',
        ]);

        Laboratory::create([
            'LaboratoryTypeID' => $request->lab_type,
            'PatientID' => $request->patient_id,
            'DoctorID' => $request->doctor_id,
            'LaboratoryDate' => $request->lab_date,
            'LaboratoryTime' => $request->lab_time,
            'TotalPrice' => $request->price,
        ]);

        return response()->json(['message' => 'Lab test created successfully']);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'lab_type' => 'required|exists:laboratory_types,LaboratoryTypeID',
            'patient_id' => 'required|exists:patients,PatientID',
            'doctor_id' => 'required|exists:doctors,DoctorID',
            'lab_date' => 'required|date',
            'lab_time' => 'required',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:Pending,Completed,Cancelled',
        ]);

        $lab = Laboratory::findOrFail($id);
        $lab->update([
            'LaboratoryTypeID' => $request->lab_type,
            'PatientID' => $request->patient_id,
            'DoctorID' => $request->doctor_id,
            'LaboratoryDate' => $request->lab_date,
            'LaboratoryTime' => $request->lab_time,
            'TotalPrice' => $request->price,
            'Status' => $request->status,
        ]);

        return response()->json(['message' => 'Lab test updated successfully']);
    }


    public function destroy($id)
    {
        $lab = Laboratory::findOrFail($id);
        $lab->delete();

        return response()->json(['message' => 'Lab test deleted successfully']);
    }

    public function storeLabType(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        LaboratoryType::create([
            'LaboratoryTypeName' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return response()->json(['message' => 'Lab type created successfully']);
    }



    public function updateLabType(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        $labType = LaboratoryType::findOrFail($id);
        $labType->update([
            'LaboratoryTypeName' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return response()->json(['message' => 'Lab type updated successfully']);
    }


    public function deleteLabType($id)
    {
        $labType = LaboratoryType::findOrFail($id);
        $labType->delete();

        return response()->json(['message' => 'Lab type deleted successfully']);
    }

    public function generateReport(Request $request)
    {
        // In real application, generate PDF report
        return response()->json(['message' => 'Report generated successfully']);
    }

    public function show($id)
    {
        $lab = Laboratory::with(['laboratoryTypeID', 'patient', 'doctor'])->findOrFail($id);

        return response()->json([
            'labType' => $lab->laboratoryType->LaboratoryTypeName,
            'patientName' => $lab->patient->FullName,
            'doctorName' => $lab->doctor->FullName,
            'labDate' => $lab->LaboratoryDate,
            'labTime' => $lab->LaboratoryTime,
            'price' => $lab->TotalPrice,
            'status' => $lab->Status,
            'result' => $lab->Result
        ]);
    }



}
