<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Laboratory;
class LabController extends Controller
{
    public function index()
    {
        $userId = Auth::id(); // Lấy UserID của bệnh nhân đang đăng nhập.

        // Lấy danh sách xét nghiệm
        $labTests = Laboratory::with(['laboratoryType', 'doctor.user'])
            ->where('UserID', $userId)
            ->orderByDesc('LaboratoryDate')
            ->get()
            ->map(function ($test) {
                return [
                    'LaboratoryID' => $test->LaboratoryID,
                    'LaboratoryDate' => $test->LaboratoryDate,
                    'LaboratoryTime' => $test->LaboratoryTime,
                    'LaboratoryTypeName' => $test->laboratoryType->LaboratoryTypeName,
                    'DoctorName' => $test->doctor->user->FullName,
                    'Status' => $test->Status,
                    'TotalPrice' => $test->TotalPrice,
                    'PaymentStatus' => $test->PaymentStatus,
                    'Result' => $test->Result,
                ];
            });

        // Tính số lượng xét nghiệm Pending và Completed
        $pendingTests = $labTests->where('Status', 'Pending')->count();
        $completedTests = $labTests->where('Status', 'Completed')->count();

        return view('patient.lab', compact('labTests', 'pendingTests', 'completedTests'));
    }

    public function downloadReport($id)
    {
        // In real application, validate and download report
        return response()->json(['message' => 'Report downloaded successfully']);
    }
} 