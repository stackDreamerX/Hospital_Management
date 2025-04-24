<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Treatment;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class TreatmentController extends Controller
{
    public function index()
    {
        // Lấy UserID hiện tại từ Auth
        $userId = Auth::id();

        // Lấy DoctorID từ bảng doctors dựa vào UserID
        $doctor = \App\Models\Doctor::where('UserID', $userId)->first();

        if (!$doctor) {
            return redirect()->back()->with('error', 'Doctor profile not found!');
        }

        // Lấy danh sách treatments của Doctor
        $treatments = Treatment::with('user')
            ->where('DoctorID', $doctor->DoctorID)
            ->orderBy('TreatmentDate', 'desc')
            ->get();

        // Lấy danh sách bệnh nhân từ bảng users (RoleID = 'patient')
        $patients = User::where('RoleID', 'patient')->get();
       
         // Lấy danh sách các loại điều trị từ bảng treatment_types
        $treatmentTypes = \App\Models\TreatmentType::all();

        return view('doctor.treatment', compact('treatments', 'patients', 'treatmentTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:users,UserID', // Đảm bảo patient_id tồn tại trong bảng users
            'treatment_type' => 'required|exists:treatment_types,TreatmentTypeID',
            'description' => 'required|string',
            'treatment_date' => 'required|date|after_or_equal:today',
            'duration' => 'required|string|max:50',
            'notes' => 'nullable|string',
            'total_price' => 'required|numeric|min:0',
        ]);

        // Lấy DoctorID từ bảng doctors
        $userId = Auth::id();
        $doctor = \App\Models\Doctor::where('UserID', $userId)->first();

        if (!$doctor) {
            return response()->json(['success' => false, 'message' => 'Doctor profile not found!'], 404);
        }
        // dd($request);
        // Tạo treatment
        $treatment = Treatment::create([
            'DoctorID' => $doctor->DoctorID,
            'UserID' => $request->patient_id,
            'TreatmentTypeID' => $request->treatment_type,
            'Description' => $request->description,
            'TreatmentDate' => $request->treatment_date,    
            'Duration' => $request->duration,
            'Notes' => $request->notes,
            'TotalPrice' => $request->total_price,
            'Status' => 'Scheduled',
        ]);

        return response()->json(['success' => true, 'message' => 'Treatment assigned successfully', 'data' => $treatment]);
    }

    public function show($id)
    {      
        $treatment = Treatment::with(['user', 'treatmentType'])
            ->where('TreatmentID', $id)
            ->firstOrFail();
        
        return response()->json([
            'TreatmentID' => $treatment->TreatmentID,
            'PatientID' => $treatment->user->UserID,
            'PatientName' => $treatment->user->FullName,
            'TreatmentTypeID' => $treatment->treatmentType->TreatmentTypeID,
            'TreatmentTypeName' => $treatment->treatmentType->TreatmentTypeName,
            'TreatmentDate' => $treatment->TreatmentDate,
            'Duration' => $treatment->Duration,
            'TotalPrice' => $treatment->TotalPrice,
            'Status' => $treatment->Status,
            'Description' => $treatment->Description,
            'Notes' => $treatment->Notes,
            'Progress' => $treatment->Progress ?? null,
        ]);
    }


    public function update(Request $request, $id)
    {
        try {
            $treatment = Treatment::findOrFail($id);

            Log::info('Update request received for treatment ID: ' . $id);
            Log::info('Request method: ' . $request->method());
            Log::info('Request data: ' . json_encode($request->all()));

            // Get status from the request - handle both direct POST and form data
            $status = $request->input('status');
            
            if (empty($status)) {
                Log::warning('Status is empty in the request');
                return response()->json(['message' => 'Status field is required'], 422);
            }
            
            Log::info('Status value: ' . $status);
            
            // Check the status field length to prevent truncation
            if (strlen($status) > 50) {
                Log::warning('Status value is too long: ' . $status);
                return response()->json(['message' => 'Status value is too long (max 50 characters)'], 422);
            }
            
            // Update the treatment status using query builder to ensure proper quoting
            $treatment->Status = trim($status);
            
            // Try to save with error handling
            try {
                $result = $treatment->save();
                Log::info('Treatment save result: ' . ($result ? 'true' : 'false'));
            } catch (\Exception $saveError) {
                Log::error('Error saving treatment: ' . $saveError->getMessage());
                throw $saveError;
            }
            
            Log::info('Treatment updated successfully');
            return response()->json(['message' => 'Treatment updated successfully']);
        } catch (\Exception $e) {
            Log::error('Error updating treatment: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['message' => 'Failed to update treatment: ' . $e->getMessage()], 500);
        }
    }




    public function destroy($id)
    {
        $treatment = Treatment::findOrFail($id);
        $treatment->delete();

        return response()->json(['success' => true, 'message' => 'Treatment cancelled successfully']);
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