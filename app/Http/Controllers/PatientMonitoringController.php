<?php

namespace App\Http\Controllers;

use App\Models\PatientMonitoring;
use App\Models\PatientWardAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PatientMonitoringController extends Controller
{
    /**
     * Store a newly created monitoring record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PatientWardAllocation  $allocation
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, PatientWardAllocation $allocation)
    {
        try {
            Log::info('Starting to store patient monitoring data', [
                'allocation_id' => $allocation->AllocationID,
                'request_data' => $request->all()
            ]);

            $validated = $request->validate([
                'blood_pressure' => 'required|string|max:10',
                'heart_rate' => 'required|integer|min:20|max:300',
                'temperature' => 'required|numeric|min:30',
                'spo2' => 'required|integer|min:50|max:100',
                'treatment_outcome' => 'required|in:improved,stable,worsened',
                'doctor_id' => 'required|exists:users,UserID',
                'notes' => 'nullable|string',
            ]);

            // Debugging - hiển thị doctor_id
            Log::info('Doctor ID from request', [
                'doctor_id' => $request->doctor_id,
                'doctor_id_exists' => $request->has('doctor_id') ? 'yes' : 'no'
            ]);

            // Kiểm tra bảng patient_monitorings đã tồn tại chưa
            try {
                DB::statement('SELECT 1 FROM patient_monitorings LIMIT 1');
                Log::info('patient_monitorings table exists');
            } catch (\Exception $e) {
                Log::error('patient_monitorings table does not exist', [
                    'error' => $e->getMessage()
                ]);
                return redirect()->back()
                    ->with('error', 'Bảng patient_monitorings chưa được tạo. Vui lòng chạy migration.')
                    ->withInput();
            }

            $monitoring = new PatientMonitoring([
                'AllocationID' => $allocation->AllocationID,
                'PatientID' => $allocation->PatientID,
                'blood_pressure' => $request->blood_pressure,
                'heart_rate' => $request->heart_rate,
                'temperature' => $request->temperature,
                'spo2' => $request->spo2,
                'treatment_outcome' => $request->treatment_outcome,
                'doctor_id' => $request->doctor_id,
                'notes' => $request->notes,
                'recorded_at' => now(),
            ]);

            Log::info('Patient monitoring data prepared', [
                'monitoring_data' => $monitoring->toArray()
            ]);

            $monitoring->save();

            Log::info('Patient monitoring data saved successfully', [
                'monitoring_id' => $monitoring->id
            ]);

            return redirect()->route('allocations.show', $allocation)
                ->with('success', 'Thông tin theo dõi bệnh nhân đã được thêm thành công');
        } catch (\Exception $e) {
            Log::error('Error storing patient monitoring data', [
                'allocation_id' => $allocation->AllocationID,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update the specified monitoring record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PatientMonitoring  $monitoring
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PatientMonitoring $monitoring)
    {
        $request->validate([
            'blood_pressure' => 'required|string|max:10',
            'heart_rate' => 'required|integer|min:20|max:300',
            'temperature' => 'required|numeric|min:30|max:45',
            'spo2' => 'required|integer|min:50|max:100',
            'treatment_outcome' => 'required|in:improved,stable,worsened',
            'doctor_id' => 'required|exists:users,UserID',
            'notes' => 'nullable|string',
        ]);

        $monitoring->update([
            'blood_pressure' => $request->blood_pressure,
            'heart_rate' => $request->heart_rate,
            'temperature' => $request->temperature,
            'spo2' => $request->spo2,
            'treatment_outcome' => $request->treatment_outcome,
            'doctor_id' => $request->doctor_id,
            'notes' => $request->notes,
        ]);

        return redirect()->route('allocations.show', $monitoring->allocation)
            ->with('success', 'Thông tin theo dõi bệnh nhân đã được cập nhật thành công');
    }

    /**
     * Remove the specified monitoring record from storage.
     *
     * @param  \App\Models\PatientMonitoring  $monitoring
     * @return \Illuminate\Http\Response
     */
    public function destroy(PatientMonitoring $monitoring)
    {
        $allocation = $monitoring->allocation;
        $monitoring->delete();

        return redirect()->route('allocations.show', $allocation)
            ->with('success', 'Thông tin theo dõi bệnh nhân đã được xóa thành công');
    }

    /**
     * Get monitoring details for AJAX requests
     *
     * @param  \App\Models\PatientMonitoring  $monitoring
     * @return \Illuminate\Http\Response
     */
    public function getDetails(PatientMonitoring $monitoring)
    {
        try {
            // Eager load relations
            $monitoring->load('doctor');

            // Return as JSON
            return response()->json($monitoring);
        } catch (\Exception $e) {
            Log::error('Error retrieving patient monitoring details', [
                'monitoring_id' => $monitoring->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'error' => 'Không thể tải thông tin chi tiết: ' . $e->getMessage()
            ], 500);
        }
    }
}