<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Treatment;

class TreatmentController extends Controller
{
    // Hiển thị danh sách tất cả các treatment
    public function index()
    {
        $treatments = Treatment::with(['treatmentType', 'doctor.user', 'user'])
            ->orderBy('TreatmentDate', 'desc')
            ->get();
    
        // Kiểm tra dữ liệu trả về
        if ($treatments->isEmpty()) {
            return view('admin.treatment', compact('treatments'));
        }
    
        return view('admin.treatment', compact('treatments'));
    }
    

    // Lấy chi tiết một treatment
    public function show($id)
{
    $treatment = Treatment::with(['treatmentType', 'doctor.user', 'user'])
        ->findOrFail($id);

    return response()->json([
        'TreatmentID' => $treatment->TreatmentID,
        'TypeName' => optional($treatment->treatmentType)->TypeName,
        'PatientName' => optional($treatment->user)->FullName,
        'DoctorName' => optional($treatment->doctor->user)->FullName,
        'TreatmentDate' => $treatment->TreatmentDate,
        'TotalPrice' => $treatment->TotalPrice,
        'Status' => $treatment->Status,
        'Description' => $treatment->Description,
    ]);
}


    // Xóa một treatment
    public function destroy($id)
{
    try {
        $treatment = Treatment::findOrFail($id);
        $treatment->delete();

        return response()->json(['message' => 'Treatment deleted successfully.']);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Failed to delete treatment: ' . $e->getMessage()], 500);
    }
}

}
