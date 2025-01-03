<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Treatment;

class TreatmentController extends Controller
{
    public function index()
    {
        // Lấy ID của bệnh nhân đang đăng nhập
        $userId = Auth::id();

        // Lấy danh sách điều trị (treatments) liên kết đến bệnh nhân
        $treatments = Treatment::with(['doctor.user', 'treatmentType'])
            ->where('UserID', $userId) // Lọc theo PatientID
            ->orderBy('TreatmentDate', 'desc')
            ->get()
            ->map(function ($treatment) {
                return [
                    'TreatmentID' => $treatment->TreatmentID,
                    'StartDate' => $treatment->TreatmentDate,
                    'EndDate' => optional($treatment->EndDate)->format('Y-m-d') ?? 'Ongoing',
                    'TreatmentName' => $treatment->treatmentType->TreatmentTypeName ?? 'Unknown', // Lấy tên Treatment
                    'DoctorName' => $treatment->doctor->user->FullName ?? 'Unknown',
                    'Status' => $treatment->Status,
                    'Description' => $treatment->Description,
                    'Cost' => $treatment->TotalPrice,
                    'Notes' => $treatment->Notes,
                ];
            });

        // Tính toán thống kê
        $ongoingCount = $treatments->filter(fn($t) => $t['Status'] !== 'Completed')->count();
        $completedCount = $treatments->filter(fn($t) => $t['Status'] === 'Completed')->count();
        $totalCost = $treatments->sum('Cost');

        // Trả về view
        return view('patient.treatment', compact('treatments', 'ongoingCount', 'completedCount', 'totalCost'));
    }
}
