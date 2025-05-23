<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use App\Models\PatientWardAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MedicationController extends Controller
{
    /**
     * Store a newly created medication in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PatientWardAllocation  $allocation
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, PatientWardAllocation $allocation)
    {
        try {
            Log::info('Starting to store medication data', [
                'allocation_id' => $allocation->AllocationID,
                'request_data' => $request->all()
            ]);

            $validated = $request->validate([
                'medication_name' => 'required|string|max:255',
                'dosage' => 'required|string|max:100',
                'frequency' => 'required|string|max:100',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'instructions' => 'nullable|string',
                'doctor_id' => 'required|exists:users,UserID',
            ]);

            // Kiểm tra bảng medications đã tồn tại chưa
            try {
                DB::statement('SELECT 1 FROM medications LIMIT 1');
                Log::info('medications table exists');
            } catch (\Exception $e) {
                Log::error('medications table does not exist', [
                    'error' => $e->getMessage()
                ]);
                return redirect()->back()
                    ->with('error', 'Bảng medications chưa được tạo. Vui lòng chạy migration.')
                    ->withInput();
            }

            $medication = new Medication([
                'AllocationID' => $allocation->AllocationID,
                'PatientID' => $allocation->PatientID,
                'medication_name' => $request->medication_name,
                'dosage' => $request->dosage,
                'frequency' => $request->frequency,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'instructions' => $request->instructions,
                'doctor_id' => $request->doctor_id,
            ]);

            Log::info('Medication data prepared', [
                'medication_data' => $medication->toArray()
            ]);

            $medication->save();

            Log::info('Medication data saved successfully', [
                'medication_id' => $medication->id
            ]);

            return redirect()->route('allocations.show', $allocation)
                ->with('success', 'Đơn thuốc đã được thêm thành công');
        } catch (\Exception $e) {
            Log::error('Error storing medication data', [
                'allocation_id' => $allocation->AllocationID,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi lưu đơn thuốc: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update the specified medication in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Medication  $medication
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Medication $medication)
    {
        $request->validate([
            'medication_name' => 'required|string|max:255',
            'dosage' => 'required|string|max:100',
            'frequency' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'instructions' => 'nullable|string',
            'doctor_id' => 'required|exists:users,UserID',
        ]);

        $medication->update([
            'medication_name' => $request->medication_name,
            'dosage' => $request->dosage,
            'frequency' => $request->frequency,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'instructions' => $request->instructions,
            'doctor_id' => $request->doctor_id,
        ]);

        return redirect()->route('allocations.show', $medication->allocation)
            ->with('success', 'Đơn thuốc đã được cập nhật thành công');
    }

    /**
     * Remove the specified medication from storage.
     *
     * @param  \App\Models\Medication  $medication
     * @return \Illuminate\Http\Response
     */
    public function destroy(Medication $medication)
    {
        $allocation = $medication->allocation;
        $medication->delete();

        return redirect()->route('allocations.show', $allocation)
            ->with('success', 'Đơn thuốc đã được xóa thành công');
    }
}