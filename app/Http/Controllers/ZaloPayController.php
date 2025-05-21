<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\DoctorTimeSlot;
use App\Services\ZaloPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ZaloPayController extends Controller
{
    protected $zaloPayService;

    public function __construct(ZaloPayService $zaloPayService)
    {
        $this->zaloPayService = $zaloPayService;
    }

    /**
     * Handle the callback from ZaloPay after payment
     */
    public function callback(Request $request)
    {
        Log::info('ZaloPay callback received', $request->all());

        // Cleanup abandoned slots older than 10 minutes
        // Note: This is also handled by a scheduled command (appointments:cleanup) that runs every 5 minutes
        $this->cleanupAbandonedSlots();

        $result = $this->zaloPayService->processCallback($request->all());

        // Get the temporary appointment
        $tempAppointment = Appointment::find($result['appointment_id']);

        if (!$tempAppointment) {
            Log::error('Could not find the appointment for payment', ['appointment_id' => $result['appointment_id']]);
            return redirect()->route('users')
                ->with('error', 'Không tìm thấy thông tin cuộc hẹn. Vui lòng liên hệ với bệnh viện để được hỗ trợ.');
        }

        // Check if we have a pending booking in session
        $bookingData = session('pending_booking');
        $pendingSlotId = session('pending_slot_id');

        if ($result['is_success']) {
            // Update the temporary appointment payment status
            $tempAppointment->payment_status = 'paid';
            $tempAppointment->payment_details = json_encode($result['raw_data']);
            $tempAppointment->save();

            // If we don't have booking data in session, try to use the temp appointment data
            if (!$bookingData) {
                Log::warning('No pending booking data in session, using temp appointment data', [
                    'appointment_id' => $tempAppointment->AppointmentID
                ]);

                // Create minimal booking data from the temp appointment
                $bookingData = [
                    'date' => $tempAppointment->AppointmentDate,
                    'time' => $tempAppointment->AppointmentTime,
                    'doctor_id' => $tempAppointment->DoctorID,
                    'reason' => $tempAppointment->Reason ?? 'Online booking',
                    'symptoms' => $tempAppointment->Symptoms,
                    'amount' => $tempAppointment->amount,
                    'email' => $tempAppointment->user->Email ?? request()->input('email', 'patient@example.com'),
                    'fullname' => $tempAppointment->user->FullName ?? 'Patient',
                ];

                // Try to find slot based on date, time and doctor
                if (!$pendingSlotId) {
                    $slot = DoctorTimeSlot::where('doctor_id', $tempAppointment->DoctorID)
                        ->where('date', $tempAppointment->AppointmentDate)
                        ->where('time', $tempAppointment->AppointmentTime)
                        ->where('status', 'booked')
                        ->first();

                    if ($slot) {
                        $pendingSlotId = $slot->id;
                    }
                }
            }

            DB::beginTransaction();
            try {
                // Find the slot
                $slot = DoctorTimeSlot::find($pendingSlotId);

                if (!$slot) {
                    throw new \Exception('Could not find the slot for booking');
                }

                // Create a real appointment from booking data
                $appointment = new Appointment();
                $appointment->AppointmentDate = $bookingData['date'];
                $appointment->AppointmentTime = $bookingData['time'];
                $appointment->Reason = $bookingData['reason'];
                $appointment->Symptoms = $bookingData['symptoms'];
                $appointment->DoctorID = $bookingData['doctor_id'];
                $appointment->Status = 'pending';
                $appointment->payment_method = 'zalopay';
                $appointment->payment_status = 'paid';
                $appointment->payment_id = $tempAppointment->payment_id;
                $appointment->amount = $bookingData['amount'];
                $appointment->payment_details = json_encode($result['raw_data']);
                $appointment->Notes = $bookingData['patient_info'];

                // Associate with user if available
                if (isset($bookingData['user_id'])) {
                    $appointment->UserID = $bookingData['user_id'];
                }

                $appointment->save();

                // Mark slot as booked and link to appointment
                $slot->status = 'booked';
                $slot->appointment_id = $appointment->AppointmentID;
                $slot->save();

                // Send confirmation email
                try {
                    $appointment->load(['doctor.user']);

                    // If no user is associated, create a temporary one for the email
                    if (empty($appointment->user)) {
                        $tempUser = new \App\Models\User();
                        $tempUser->FullName = $bookingData['fullname'];
                        $tempUser->Email = $bookingData['email'];
                        $appointment->user = $tempUser;
                    }

                    // Send appointment confirmation email
                    \Illuminate\Support\Facades\Mail::to($bookingData['email'])
                        ->send(new \App\Mail\AppointmentCreated($appointment));

                    // Send payment confirmation email
                    \Illuminate\Support\Facades\Mail::to($bookingData['email'])
                        ->send(new \App\Mail\AppointmentPaymentConfirmed($appointment));

                    Log::info('Payment confirmation email sent', ['email' => $bookingData['email']]);
                } catch (\Exception $e) {
                    Log::error('Failed to send payment confirmation email: ' . $e->getMessage());
                }

                DB::commit();

                // Clear session data
                Session::forget(['pending_booking', 'pending_slot_id']);

                return redirect()->route('booking.thank-you', ['appointmentId' => $appointment->AppointmentID])
                    ->with('success', 'Thanh toán thành công qua ZaloPay! Lịch hẹn của bạn đã được xác nhận.');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error processing successful ZaloPay payment', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return redirect()->route('users')
                    ->with('error', 'Đã có lỗi xảy ra trong quá trình xử lý thanh toán. Vui lòng liên hệ với bệnh viện để được hỗ trợ.');
            }
        } else {
            // Payment failed
            Log::warning('ZaloPay payment failed', [
                'response_code' => $result['response_code'],
                'transaction_status' => $result['transaction_status']
            ]);

            // Release the slot that was on hold
            if ($pendingSlotId) {
                $slot = DoctorTimeSlot::find($pendingSlotId);
                if ($slot) {
                    $slot->status = 'available';
                    $slot->appointment_id = null;
                    $slot->save();
                }
            }

            // Clear session data
            Session::forget(['pending_booking', 'pending_slot_id']);

            // Update the temporary appointment status
            $tempAppointment->payment_status = 'failed';
            $tempAppointment->payment_details = json_encode($result['raw_data']);
            $tempAppointment->save();

            return redirect()->route('users')
                ->with('error', 'Thanh toán không thành công qua ZaloPay. Vui lòng thử lại hoặc chọn phương thức thanh toán khác.');
        }
    }

    /**
     * Cleanup slots that were marked as booked but have no appointment_id
     * and are older than 10 minutes
     */
    private function cleanupAbandonedSlots()
    {
        try {
            // Find all slots that are marked as 'booked' but have no appointment_id
            // and were updated more than 10 minutes ago
            $tenMinutesAgo = now()->subMinutes(10);

            $abandonedSlots = \App\Models\DoctorTimeSlot::where('status', 'booked')
                ->whereNull('appointment_id')
                ->where('updated_at', '<', $tenMinutesAgo)
                ->get();

            if ($abandonedSlots->count() > 0) {
                Log::info("Found {$abandonedSlots->count()} abandoned slots to cleanup");

                foreach ($abandonedSlots as $slot) {
                    Log::info("Freeing abandoned slot", [
                        'slot_id' => $slot->id,
                        'date' => $slot->date,
                        'time' => $slot->time,
                        'updated_at' => $slot->updated_at
                    ]);

                    $slot->status = 'available';
                    $slot->save();
                }
            }
        } catch (\Exception $e) {
            Log::error("Error cleaning up abandoned slots: " . $e->getMessage());
            // Do not throw the exception - this is just a cleanup routine
        }
    }
}