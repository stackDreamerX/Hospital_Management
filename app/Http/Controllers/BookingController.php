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
    public function showBookingForm($doctorId, $slotId = null)
    {
        $doctor = Doctor::with('user')->findOrFail($doctorId);
        $selectedSlot = null;

        if ($slotId) {
            $selectedSlot = DoctorTimeSlot::where('id', $slotId)
                ->where('status', 'available')
                ->where('doctor_id', $doctorId)
                ->firstOrFail();
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
        $request->validate([
            'doctor_id' => 'required|exists:doctors,DoctorID',
            'slot_id' => 'required|exists:doctor_time_slots,id',
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
        ]);

        // Verify slot is still available
        $slot = DoctorTimeSlot::where('id', $request->slot_id)
            ->where('status', 'available')
            ->where('doctor_id', $request->doctor_id)
            ->firstOrFail();

        DB::beginTransaction();

        try {
            // Create the appointment
            $appointment = new Appointment();
            $appointment->AppointmentDate = $slot->date;
            $appointment->AppointmentTime = $slot->time;
            $appointment->Reason = $request->reason;
            $appointment->Symptoms = $request->symptoms;
            $appointment->DoctorID = $request->doctor_id;
            $appointment->Status = 'pending';

            // If user is logged in, associate with user
            if (Auth::check()) {
                $appointment->UserID = Auth::id();
            } else {
                // For non-authenticated users, use a guest user ID
                // First check if guest user exists, if not create one
                $guestUser = \App\Models\User::where('Email', 'guest@medicalhospital.com')->first();
                if (!$guestUser) {
                    $guestUser = new \App\Models\User();
                    $guestUser->Email = 'guest@medicalhospital.com';
                    $guestUser->FullName = 'Guest User';
                    $guestUser->Password = bcrypt('guestpassword'); // Set a strong random password
                    $guestUser->PhoneNumber = '0000000000';
                    $guestUser->UserType = 'guest';
                    $guestUser->save();
                }
                $appointment->UserID = $guestUser->UserID;
            }

            // Add patient details as notes
            $patientInfo = "Patient Information:\n";
            $patientInfo .= "Name: " . $request->fullname . "\n";
            $patientInfo .= "Gender: " . $request->gender . "\n";
            $patientInfo .= "Birthdate: " . $request->birthdate . "\n";
            $patientInfo .= "Phone: " . $request->phone . "\n";
            $patientInfo .= "Email: " . $request->email . "\n";
            $patientInfo .= "Address: " . $request->address . "\n";
            $patientInfo .= "Province: " . $request->province . "\n";
            $patientInfo .= "District: " . $request->district;

            $appointment->Notes = $patientInfo;
            $appointment->save();

            // Mark slot as booked and link to appointment
            $slot->status = 'booked';
            $slot->appointment_id = $appointment->AppointmentID;
            $slot->save();

            DB::commit();

            return redirect()->route('booking.thank-you', ['appointmentId' => $appointment->AppointmentID])
                ->with('success', 'Appointment booked successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'There was a problem booking your appointment: ' . $e->getMessage());
        }
    }

    public function thankYou($appointmentId)
    {
        $appointment = Appointment::with(['doctor.user'])->findOrFail($appointmentId);
        return view('booking.thank-you', compact('appointment'));
    }
}