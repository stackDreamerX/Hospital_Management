<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\DoctorTimeSlot;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function showBookingForm($id, $slot = null)
    {
        \Illuminate\Support\Facades\Log::info('Booking form accessed', [
            'doctor_id' => $id,
            'slot' => $slot,
            'raw_slot' => request()->slot,
            'url' => request()->fullUrl(),
            'request_method' => request()->method(),
            'all_parameters' => request()->all(),
            'route_parameters' => request()->route()->parameters(),
            'is_authenticated' => \Illuminate\Support\Facades\Auth::check()
        ]);

        // Check if user is authenticated
        if (!\Illuminate\Support\Facades\Auth::check()) {
            // Save the desired doctor ID and slot in session
            session(['booking_doctor_id' => $id, 'booking_slot_id' => $slot]);

            // Redirect to login page with intended URL
            return redirect()->route('login')
                ->with('message', 'Vui lòng đăng nhập để tiếp tục đặt lịch khám.')
                ->withInput(['redirect_url' => route('doctor.booking', ['id' => $id, 'slot' => $slot])]);
        }

        $doctor = Doctor::with('user')->findOrFail($id);
        $selectedSlot = null;

        if ($slot) {
            try {
                // Check if it's a virtual slot (starts with 'slot_')
                if (is_string($slot) && strpos($slot, 'slot_') === 0) {
                    \Illuminate\Support\Facades\Log::info('Processing virtual slot', [
                        'doctor_id' => $id,
                        'slot_id' => $slot
                    ]);

                    // Extract date and time from the slot ID format: slot_YYYYMMDDHHMMSS
                    $dateTimeStr = substr($slot, 5); // Remove "slot_" prefix

                    if (strlen($dateTimeStr) >= 12) {
                        $year = substr($dateTimeStr, 0, 4);
                        $month = substr($dateTimeStr, 4, 2);
                        $day = substr($dateTimeStr, 6, 2);
                        $hour = substr($dateTimeStr, 8, 2);
                        $minute = substr($dateTimeStr, 10, 2);

                        // Validate the extracted date components
                        if (!checkdate((int)$month, (int)$day, (int)$year) ||
                            (int)$hour >= 24 || (int)$minute >= 60) {
                            throw new \Exception('Invalid date/time in slot ID');
                        }

                        // Create a virtual slot object
                        $selectedSlot = new \stdClass();
                        $selectedSlot->doctor_id = $id;
                        $selectedSlot->id = $slot; // Keep the original slot ID for virtual slots
                        $selectedSlot->date = "$year-$month-$day";
                        $selectedSlot->time = "$hour:$minute:00";
                        $selectedSlot->status = 'available';

                        \Illuminate\Support\Facades\Log::info('Created virtual slot', [
                            'slot' => $selectedSlot
                        ]);
                    } else {
                        throw new \Exception('Invalid slot ID format');
                    }
                } else {
                    // Regular database slot
                    $selectedSlot = DoctorTimeSlot::where('id', $slot)
                        ->where('status', 'available')
                        ->where('doctor_id', $id)
                        ->firstOrFail();
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Invalid slot selected: ', [
                    'doctor_id' => $id,
                    'slot_id' => $slot,
                    'error' => $e->getMessage()
                ]);
                return redirect()->route('doctor.schedule', $id)
                    ->with('error', 'The selected time slot is not available. Please choose another time.');
            }
        }

        // Get all available provinces in Vietnam for the form
        $provinces = [
            'Hà Nội', 'TP Hồ Chí Minh', 'Đà Nẵng', 'Hải Phòng', 'Cần Thơ',
            'An Giang', 'Bà Rịa - Vũng Tàu', 'Bắc Giang', 'Bắc Kạn', 'Bạc Liêu',
            'Bắc Ninh', 'Bến Tre', 'Bình Định', 'Bình Dương', 'Bình Phước',
            'Bình Thuận', 'Cà Mau', 'Cao Bằng', 'Đắk Lắk', 'Đắk Nông',
            'Điện Biên', 'Đồng Nai', 'Đồng Tháp', 'Gia Lai', 'Hà Giang',
            'Hà Nam', 'Hà Tĩnh', 'Hải Dương', 'Hậu Giang', 'Hòa Bình',
            'Hưng Yên', 'Khánh Hòa', 'Kiên Giang', 'Kon Tum', 'Lai Châu',
            'Lâm Đồng', 'Lạng Sơn', 'Lào Cai', 'Long An', 'Nam Định',
            'Nghệ An', 'Ninh Bình', 'Ninh Thuận', 'Phú Thọ', 'Phú Yên',
            'Quảng Bình', 'Quảng Nam', 'Quảng Ngãi', 'Quảng Ninh', 'Quảng Trị',
            'Sóc Trăng', 'Sơn La', 'Tây Ninh', 'Thái Bình', 'Thái Nguyên',
            'Thanh Hóa', 'Thừa Thiên Huế', 'Tiền Giang', 'Trà Vinh', 'Tuyên Quang',
            'Vĩnh Long', 'Vĩnh Phúc', 'Yên Bái'
        ];

        return view('booking.form', compact('doctor', 'selectedSlot', 'provinces'));
    }

    public function showDoctorSchedule($doctorId)
    {
        try {
            $doctor = Doctor::with('user')->findOrFail($doctorId);

            // Log doctor information
            \Illuminate\Support\Facades\Log::info("Showing schedule for doctor", [
                'doctor_id' => $doctorId,
                'doctor_name' => $doctor->user->FullName
            ]);

            // Get the next 7 days
            $dates = [];
            $today = Carbon::today();

            for ($i = 0; $i < 7; $i++) {
                $date = $today->copy()->addDays($i);
                $dates[] = [
                    'date' => $date->format('Y-m-d'),
                    'day' => $date->format('d'),
                    'month' => $date->format('m'),
                    'year' => $date->format('Y'),
                    'day_name' => $date->format('l'),
                    'day_name_short' => $date->format('D'),
                ];
            }

            // Get available time slots for each day
            $timeSlots = [];
            $totalSlots = 0;

            foreach ($dates as $date) {
                $slots = DoctorTimeSlot::where('doctor_id', $doctorId)
                    ->where('date', $date['date'])
                    ->orderBy('time')
                    ->get();

                $timeSlots[$date['date']] = $slots;
                $totalSlots += $slots->count();

                // Log the slots found for this date
                \Illuminate\Support\Facades\Log::info("Time slots for date {$date['date']}", [
                    'doctor_id' => $doctorId,
                    'date' => $date['date'],
                    'slot_count' => $slots->count()
                ]);
            }

            // Log summary
            \Illuminate\Support\Facades\Log::info("Schedule summary", [
                'doctor_id' => $doctorId,
                'total_dates' => count($dates),
                'total_slots' => $totalSlots
            ]);

            return view('booking.schedule', compact('doctor', 'dates', 'timeSlots'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Error showing doctor schedule", [
                'doctor_id' => $doctorId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('users')->with('error', 'There was an error viewing the doctor\'s schedule. Please try again later.');
        }
    }

    public function store(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Booking form submitted', [
            'doctor_id' => $request->doctor_id,
            'slot_id' => $request->slot_id,
            'all_data' => $request->all()
        ]);

        // Validate basic data first
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,DoctorID',
            'fullname' => 'required|string|max:100',
            'gender' => 'required|in:male,female',
            'birthdate' => 'required|date',
            'phone' => 'required|string|max:15',
            'email' => 'required|email',
            'address' => 'required|string',
            'province' => 'required|string',
            'district' => 'required|string',
            'reason' => 'required|string',
            'symptoms' => 'nullable|string',
            'payment_method' => 'required|in:cash,vnpay',
        ]);

        // Handle slot separately to allow for virtual slots with 'slot_' prefix
        $slotId = $request->slot_id;
        \Illuminate\Support\Facades\Log::info('Processing slot', ['slot_id' => $slotId]);

        try {
            // Find or create the slot
            if (is_string($slotId) && strpos($slotId, 'slot_') === 0) {
                \Illuminate\Support\Facades\Log::info('Processing virtual slot for booking', ['slot_id' => $slotId]);

                // Extract date and time from the slot ID
                $dateTimeStr = substr($slotId, 5); // Remove 'slot_' prefix

                // For virtual slots format: slot_YYYYMMDDHHMMSS
                if (strlen($dateTimeStr) >= 12) {
                    $year = substr($dateTimeStr, 0, 4);
                    $month = substr($dateTimeStr, 4, 2);
                    $day = substr($dateTimeStr, 6, 2);
                    $hour = substr($dateTimeStr, 8, 2);
                    $minute = substr($dateTimeStr, 10, 2);

                    // Validate date components
                    if (!checkdate((int)$month, (int)$day, (int)$year) ||
                        (int)$hour >= 24 || (int)$minute >= 60) {
                        throw new \Exception('Invalid date/time in slot ID');
                    }

                    // Format date and time properly
                    $date = "$year-$month-$day";
                    $time = "$hour:$minute:00";

                    \Illuminate\Support\Facades\Log::info('Extracted date and time from virtual slot', [
                        'date' => $date,
                        'time' => $time
                    ]);

                    // Check if a slot already exists for this time
                    $existingSlot = DoctorTimeSlot::where('doctor_id', $request->doctor_id)
                        ->where('date', $date)
                        ->where('time', $time)
                        ->first();

                    if ($existingSlot) {
                        // Use existing slot if available
                        if ($existingSlot->status !== 'available') {
                            // Log detailed information about the unavailable slot
                            \Illuminate\Support\Facades\Log::warning('Slot is unavailable', [
                                'slot_id' => $existingSlot->id,
                                'status' => $existingSlot->status,
                                'date' => $existingSlot->date,
                                'time' => $existingSlot->time,
                                'appointment_id' => $existingSlot->appointment_id
                            ]);

                            // For slots that are 'booked' but have no appointment_id, they may be abandoned
                            // from a previous failed payment. Let's reclaim them.
                            if ($existingSlot->status === 'booked' && $existingSlot->appointment_id === null) {
                                \Illuminate\Support\Facades\Log::info('Reclaiming abandoned slot', [
                                    'slot_id' => $existingSlot->id
                                ]);
                                $existingSlot->status = 'available';
                                $existingSlot->save();
                                $slot = $existingSlot;
                            } else {
                                throw new \Exception('This time slot is no longer available');
                            }
                        } else {
                            $slot = $existingSlot;
                            \Illuminate\Support\Facades\Log::info('Using existing slot', [
                                'slot_id' => $slot->id
                            ]);
                        }
                    } else {
                        // Create a real slot in the database
                        $slot = new DoctorTimeSlot();
                        $slot->doctor_id = $request->doctor_id;
                        $slot->date = $date;
                        $slot->time = $time;
                        $slot->status = 'available';
                        $slot->save();

                        \Illuminate\Support\Facades\Log::info('Created real slot from virtual slot', [
                            'new_slot_id' => $slot->id,
                            'original_virtual_id' => $slotId
                        ]);
                    }
                } else {
                    throw new \Exception('Invalid virtual slot format');
                }
            } else {
                // Now verify the slot (either existing or newly created)
                $slot = DoctorTimeSlot::where('id', $slotId)
                    ->where('doctor_id', $request->doctor_id)
                    ->first();

                if (!$slot) {
                    throw new \Exception('Selected time slot not found');
                }

                if ($slot->status !== 'available') {
                    // Log detailed information about the unavailable slot
                    \Illuminate\Support\Facades\Log::warning('Regular slot is unavailable', [
                        'slot_id' => $slot->id,
                        'status' => $slot->status,
                        'date' => $slot->date,
                        'time' => $slot->time,
                        'appointment_id' => $slot->appointment_id
                    ]);

                    // For slots that are 'booked' but have no appointment_id, they may be abandoned
                    if ($slot->status === 'booked' && $slot->appointment_id === null) {
                        \Illuminate\Support\Facades\Log::info('Reclaiming abandoned regular slot', [
                            'slot_id' => $slot->id
                        ]);
                        $slot->status = 'available';
                        $slot->save();
                    } else {
                        throw new \Exception('This time slot is no longer available');
                    }
                }
            }

            // Get doctor's pricing
            $doctor = Doctor::find($request->doctor_id);
            $amount = $doctor->pricing_vn ?? 300000; // Default price if not set

            // Prepare patient info
            $patientInfo = "Patient Information:\n";
            $patientInfo .= "Name: " . $request->fullname . "\n";
            $patientInfo .= "Gender: " . $request->gender . "\n";
            $patientInfo .= "Birthdate: " . $request->birthdate . "\n";
            $patientInfo .= "Phone: " . $request->phone . "\n";
            $patientInfo .= "Email: " . $request->email . "\n";
            $patientInfo .= "Address: " . $request->address . "\n";
            $patientInfo .= "Province: " . $request->province . "\n";
            $patientInfo .= "District: " . $request->district;

            // Handle different payment methods
            if ($request->payment_method === 'vnpay') {
                DB::beginTransaction();
                try {
                    // For VNPay, save booking data in session and redirect to payment gateway
                    $bookingData = [
                        'doctor_id' => $request->doctor_id,
                        'slot_id' => $slot->id,
                        'date' => $slot->date,
                        'time' => $slot->time,
                        'reason' => $request->reason,
                        'symptoms' => $request->symptoms,
                        'payment_method' => 'vnpay',
                        'amount' => $amount,
                        'patient_info' => $patientInfo,
                        'user_id' => Auth::id(),
                        'fullname' => $request->fullname,
                        'email' => $request->email
                    ];

                    // Store the slot id to temporarily reserve it
                    session(['pending_booking' => $bookingData, 'pending_slot_id' => $slot->id]);

                    // Mark slot as temporarily reserved
                    $slot->status = 'booked';
                    $slot->save();

                    \Illuminate\Support\Facades\Log::info('Redirecting to VNPay payment gateway', [
                        'booking_data' => $bookingData
                    ]);

                    // Create a temporary appointment for VNPay reference
                    $tempAppointment = new Appointment();
                    $tempAppointment->AppointmentDate = $slot->date;
                    $tempAppointment->AppointmentTime = $slot->time;
                    $tempAppointment->DoctorID = $request->doctor_id;
                    $tempAppointment->UserID = Auth::id(); // Add the user ID
                    $tempAppointment->payment_method = 'vnpay';
                    $tempAppointment->payment_status = 'pending';
                    $tempAppointment->amount = $amount;
                    $tempAppointment->save();

                    DB::commit();

                    $vnpayService = new \App\Services\VNPayService();
                    $paymentUrl = $vnpayService->createPaymentUrl($tempAppointment);

                    return redirect()->away($paymentUrl);
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            } else {
                // For cash payment, create the appointment immediately
                DB::beginTransaction();

                // Create the appointment
                $appointment = new Appointment();
                $appointment->AppointmentDate = $slot->date;
                $appointment->AppointmentTime = $slot->time;
                $appointment->Reason = $request->reason;
                $appointment->Symptoms = $request->symptoms;
                $appointment->DoctorID = $request->doctor_id;
                $appointment->Status = 'pending';
                $appointment->payment_method = 'cash';
                $appointment->payment_status = 'pending';
                $appointment->amount = $amount;
                $appointment->Notes = $patientInfo;

                // If user is logged in, associate with user
                if (Auth::check()) {
                    $appointment->UserID = Auth::id();
                }

                $appointment->save();

                \Illuminate\Support\Facades\Log::info('Appointment created', [
                    'appointment_id' => $appointment->AppointmentID,
                    'doctor_id' => $appointment->DoctorID,
                    'date' => $appointment->AppointmentDate,
                    'time' => $appointment->AppointmentTime,
                    'payment_method' => $appointment->payment_method
                ]);

                // Mark slot as booked and link to appointment
                $slot->status = 'booked';
                $slot->appointment_id = $appointment->AppointmentID;
                $slot->save();

                \Illuminate\Support\Facades\Log::info('Slot marked as booked', [
                    'slot_id' => $slot->id,
                    'appointment_id' => $appointment->AppointmentID
                ]);

                // Send notification email for cash payment
                try {
                    // Load necessary relationships for the email
                    $appointment->load(['doctor.user']);

                    // Create a fake user model for non-authenticated users
                    if (!Auth::check()) {
                        $tempUser = new \App\Models\User();
                        $tempUser->FullName = $request->fullname;
                        $tempUser->Email = $request->email;
                        $appointment->user = $tempUser;
                    }

                    // Send email
                    \Illuminate\Support\Facades\Mail::to($request->email)
                        ->send(new \App\Mail\AppointmentCreated($appointment));

                    \Illuminate\Support\Facades\Log::info('Appointment creation email sent to ' . $request->email);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Failed to send appointment email: ' . $e->getMessage());
                    // Don't throw error - we don't want to fail booking just because email failed
                }

                DB::commit();

                // For cash payment, redirect to thank you page
                \Illuminate\Support\Facades\Log::info('Cash payment selected, redirecting to thank you page', [
                    'appointment_id' => $appointment->AppointmentID
                ]);

                return redirect()->route('booking.thank-you', ['appointmentId' => $appointment->AppointmentID])
                    ->with('success', 'Appointment booked successfully! Please make the payment at the hospital reception.');
            }
        } catch (\Exception $e) {
            // Roll back any DB changes if there was an exception
            if (isset($slot) && $slot->isDirty()) {
                $slot->status = 'available';
                $slot->save();
            } else if (isset($slot) && $slot->status === 'booked' && !$slot->appointment_id) {
                \Illuminate\Support\Facades\Log::info('Freeing booked slot due to exception', [
                    'slot_id' => $slot->id,
                    'status' => $slot->status
                ]);
                $slot->status = 'available';
                $slot->save();
            }

            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            \Illuminate\Support\Facades\Log::error('Error creating booking', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'slot_id' => $slot->id ?? null,
                'slot_status' => $slot->status ?? null,
                'virtual_slot' => $slotId ?? null
            ]);

            return back()->withInput()->with('error', 'There was a problem booking your appointment: ' . $e->getMessage());
        }
    }

    public function thankYou($appointmentId)
    {
        $appointment = Appointment::with(['doctor.user'])->findOrFail($appointmentId);
        return view('booking.thank-you', compact('appointment'));
    }
}