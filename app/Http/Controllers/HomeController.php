<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Dompdf\Dompdf;
use Dompdf\Options;

class HomeController extends Controller
{
    public function index()
    {
        return view('pages.home');
    }

    public function sign_in(Request $request)
    {
        // If redirect URL is provided in query parameter, store it in session
        if ($request->has('redirect_to')) {
            session()->put('url.intended', $request->input('redirect_to'));
        }

        return view('pages.sign_in');
    }

    public function sign_up()
    {
        return view('pages.sign_up');
    }

    public function home_dashboard(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            // Điều hướng đến trang dashboard
            $request->session()->regenerate();

            // Check if there was a booking in progress
            $doctorId = session('booking_doctor_id');
            $slotId = session('booking_slot_id');

            if ($doctorId && $slotId) {
                // Clear the session data
                session()->forget(['booking_doctor_id', 'booking_slot_id']);

                // Redirect to the booking page
                return redirect()->route('doctor.booking', [
                    'id' => $doctorId,
                    'slot' => $slotId
                ])->with('message', 'Đăng nhập thành công, giờ bạn có thể tiếp tục đặt lịch khám.');
            }

            // Set the last activity time for session timeout
            Session::put('lastActivityTime', \Carbon\Carbon::now());

            // Check if there's a previous URL stored in session that we should redirect to
            $intendedUrl = session('url.intended');

            if ($intendedUrl) {
                // Clear the intended URL from session
                session()->forget('url.intended');
                return redirect($intendedUrl)->with('success', 'Đăng nhập thành công.');
            }

            // Default redirection based on role if no intended URL
            if (Auth::user()->RoleID === 'patient') {
                return redirect()->route('users.dashboard');
            } elseif (Auth::user()->RoleID === 'doctor') {
                return redirect()->route('doctor.dashboard');
            } else {
                return redirect()->route('admin.dashboard');
            }
        } else {
            // Đăng nhập thất bại
            return redirect()->back()->withErrors(['message' => 'Tài khoản hoặc mật khẩu không đúng.']);
        }
    }

    public function home_logout()
    {
        // Đăng xuất người dùng
        Auth::logout();
        // Chuyển hướng về trang chủ
        return redirect('/')->with('success', 'You have been logged out successfully');
    }

    public function register(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'FullName' => 'required|string|max:100',
            'Email' => 'required|email|unique:users,Email',
            'password' => 'required|min:6|confirmed',
            'PhoneNumber' => 'required|string|max:15',
            'RoleID' => 'required|in:patient,doctor,administrator',
            'username' => 'required|string|max:50|unique:users,username',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        //dd($request->all());
        // Tạo user mới
        User::create([
            'RoleID' => $request->RoleID,
            'username' => $request->username,
            'FullName' => $request->FullName,
            'Email' => $request->Email,
            'password' => Hash::make($request->password),
            'PhoneNumber' => $request->PhoneNumber,

        ]);

        // Chuyển hướng sau khi đăng ký thành công
        return redirect('/sign-in')->with('success', 'Tài khoản đã được tạo thành công! Vui lòng đăng nhập.');
    }

    public function staff()
    {
        // Get list of specialties for the dropdown
        $specialties = Doctor::distinct()->pluck('Speciality')->toArray();

        // Get all doctors with their user information and ratings
        $doctors = Doctor::with(['user', 'ratings'])->get();

        // Calculate average rating for each doctor
        foreach ($doctors as $doctor) {
            $doctor->avgRating = $doctor->getAverageRatingAttribute() ?? 0;
            $doctor->ratingCount = $doctor->ratings->where('status', 'approved')
                            ->whereNotNull('doctor_rating')->count();
        }

        return view('pages.staff', [
            'doctors' => $doctors,
            'specialties' => $specialties,
            'search' => false
        ]);
    }

    public function searchDoctors(Request $request)
    {
        // Retrieve search inputs
        $name = $request->input('doctor_name', '');
        $specialty = $request->input('specialty');
        $location = $request->input('location');
        $insurance = $request->input('insurance');

        // Get list of specialties for the dropdown
        $specialties = Doctor::distinct()->pluck('Speciality')->toArray();

        // Start the query with doctor relationships
        $query = Doctor::with(['user', 'ratings']);

        // Apply filters if present
        if ($specialty) {
            $query->where('Speciality', $specialty);
        }

        // Name search needs to check the user table
        if ($name) {
            $query->whereHas('user', function($q) use ($name) {
                $q->where('FullName', 'LIKE', "%$name%");
            });
        }

        // For location and insurance, you'd need to add these to your database schema
        // This is placeholder code assuming these fields exist or will exist
        if ($location) {
            // $query->where('Location', $location);
            // Comment out until location field is added to schema
        }

        if ($insurance) {
            // $query->where('Insurance', $insurance);
            // Comment out until insurance field is added to schema
        }

        // Fetch filtered results
        $doctors = $query->get();

        // Calculate average rating for each doctor
        foreach ($doctors as $doctor) {
            $doctor->avgRating = $doctor->getAverageRatingAttribute() ?? 0;
            $doctor->ratingCount = $doctor->ratings->where('status', 'approved')
                            ->whereNotNull('doctor_rating')->count();
        }

        // Return the view with the results
        return view('pages.staff', [
            'doctors' => $doctors,
            'specialties' => $specialties,
            'search' => true
        ]);
    }


    public function locations()
    {
        return view('pages.locations');
    }

    public function patients()
    {
        return view('pages.patients');
    }

    public function appointments()
    {
        if (!Auth::check()) {
            // Store current URL in session before redirecting
            session()->put('url.intended', url()->current());
            return redirect('/sign-in')->with('message', 'Please login to access appointments');
        }

        // Get same data as the patient appointments controller
        $patientId = Auth::user()->UserID;
        $appointments = \App\Models\Appointment::with('doctor')
            ->where('UserID', $patientId)
            ->orderByDesc('AppointmentDate')
            ->get();
        $doctors = \App\Models\Doctor::with('user')->get();
        $pendingCount = $appointments->where('Status', 'pending')->count();
        $approvedCount = $appointments->where('Status', 'approved')->count();

        // Get the appointments that have already been rated by this user
        $ratedAppointmentIds = \App\Models\Rating::where('user_id', $patientId)
            ->whereNotNull('appointment_id')
            ->pluck('appointment_id')
            ->toArray();

        // Add a flag to each appointment indicating if it has been rated
        foreach ($appointments as $appointment) {
            $appointment->has_rating = in_array($appointment->AppointmentID, $ratedAppointmentIds);
        }

        // Render the appointments view directly
        return view('pages.appointments', compact('doctors', 'appointments', 'pendingCount', 'approvedCount', 'ratedAppointmentIds'));
    }

    public function appointmentStore(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'User is not logged in'], 403);
            }
            $validated = $request->validate([
                'appointment_date' => 'required|date|after:today',
                'appointment_time' => 'required',
                'reason' => 'required|string|max:255',
                'symptoms' => 'required|string',
                'notes' => 'nullable|string',
                'doctor_id' => 'required|exists:doctors,DoctorID',
            ]);

            $appointment = \App\Models\Appointment::create([
                'AppointmentDate' => $validated['appointment_date'],
                'AppointmentTime' => $validated['appointment_time'],
                'Reason' => $validated['reason'],
                'Symptoms' => $validated['symptoms'],
                'Notes' => $validated['notes'],
                'UserID' => Auth::check() ? Auth::user()->UserID : null,
                'DoctorID' => $validated['doctor_id'],
                'Status' => 'pending',
            ]);

            // Load the relationships for email
            $appointment->load(['user', 'doctor.user']);

            // Send email notification
            try {
                Mail::to($appointment->user->Email)->send(new \App\Mail\AppointmentCreated($appointment));
                Log::info('Appointment creation email sent successfully to ' . $appointment->user->Email);
            } catch (\Exception $e) {
                Log::error('Error sending appointment creation email: ' . $e->getMessage());
            }

            return response()->json(['message' => 'Appointment created successfully']);
        } catch (\Exception $e) {
            Log::error('Error creating appointment: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create appointment.'], 500);
        }
    }

    public function appointmentUpdate(Request $request, $id)
    {
        $appointment = \App\Models\Appointment::findOrFail($id);
        if ($appointment->Status != 'pending') {
            return response()->json(['error' => 'Không thể sửa cuộc hẹn này!'], 403);
        }

        // Xác thực dữ liệu
        $validated = $request->validate([
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required',
            'reason' => 'required|string|max:255',
            'symptoms' => 'required|string',
            'notes' => 'nullable|string',
            'doctor_id' => 'required|exists:doctors,DoctorID',
        ]);

        try {
            // Cập nhật dữ liệu
            $appointment->update([
                'AppointmentDate' => $validated['appointment_date'],
                'AppointmentTime' => $validated['appointment_time'],
                'Reason' => $validated['reason'],
                'Symptoms' => $validated['symptoms'],
                'Notes' => $validated['notes'],
                'DoctorID' => $validated['doctor_id'],
            ]);

            // Load the relationships for email
            $appointment->load(['user', 'doctor.user']);

            // Send email notification
            try {
                Mail::to($appointment->user->Email)->send(new \App\Mail\AppointmentUpdated($appointment));
                Log::info('Appointment update email sent successfully to ' . $appointment->user->Email);
            } catch (\Exception $e) {
                Log::error('Error sending appointment update email: ' . $e->getMessage());
            }

            return response()->json(['message' => 'Cập nhật cuộc hẹn thành công!']);
        } catch (\Exception $e) {
            Log::error('Error updating appointment: ' . $e->getMessage());
            return response()->json(['error' => 'Không thể cập nhật cuộc hẹn.'], 500);
        }
    }

    public function appointmentDestroy($id)
    {
        $appointment = \App\Models\Appointment::findOrFail($id);
        if ($appointment->Status != 'pending') {
            return response()->json(['error' => 'Không thể hủy cuộc hẹn này!'], 403);
        }

        try {
            // Load the relationships before deleting
            $appointment->load(['user', 'doctor.user']);

            // Store appointment data for email
            $appointmentData = $appointment->toArray();
            $appointmentData['user'] = $appointment->user->toArray();
            $appointmentData['doctor'] = $appointment->doctor ? $appointment->doctor->toArray() : null;
            $appointmentData['doctor']['user'] = $appointment->doctor && $appointment->doctor->user
                ? $appointment->doctor->user->toArray()
                : null;

            // Get user email before deletion
            $userEmail = $appointment->user->Email;

            // Create a new appointment instance with the stored data
            // This is needed because the original will be deleted
            $appointmentForEmail = new \App\Models\Appointment($appointmentData);
            $appointmentForEmail->user = new \App\Models\User($appointmentData['user']);
            if ($appointmentData['doctor']) {
                $doctorModel = new \App\Models\Doctor($appointmentData['doctor']);
                if ($appointmentData['doctor']['user']) {
                    $doctorModel->user = new \App\Models\User($appointmentData['doctor']['user']);
                }
                $appointmentForEmail->doctor = $doctorModel;
            }

            // Delete the appointment
            $appointment->delete();

            // Send email notification
            try {
                Mail::to($userEmail)->send(new \App\Mail\AppointmentCancelled($appointmentForEmail));
                Log::info('Appointment cancellation email sent successfully to ' . $userEmail);
            } catch (\Exception $e) {
                Log::error('Error sending appointment cancellation email: ' . $e->getMessage());
            }

            return response()->json(['message' => 'Hủy cuộc hẹn thành công!']);
        } catch (\Exception $e) {
            Log::error('Error cancelling appointment: ' . $e->getMessage());
            return response()->json(['error' => 'Không thể hủy cuộc hẹn.'], 500);
        }
    }

    public function appointmentShow($id)
    {
        $appointment = \App\Models\Appointment::where('AppointmentID', $id)
            ->where('UserID', Auth::user()->UserID)
            ->firstOrFail();

        return response()->json($appointment);
    }

    public function appointmentShowDetail($id)
    {
        try {
            $appointment = \App\Models\Appointment::with('doctor.user')->findOrFail($id);

            return response()->json([
                'AppointmentDate' => $appointment->AppointmentDate,
                'AppointmentTime' => $appointment->AppointmentTime,
                'DoctorName' => $appointment->doctor->user->FullName ?? 'Chưa được chỉ định',
                'Status' => $appointment->Status,
                'Reason' => $appointment->Reason,
                'Symptoms' => $appointment->Symptoms,
                'Notes' => $appointment->Notes,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không tìm thấy thông tin cuộc hẹn.'], 404);
        }
    }

    public function doctorProfile($id)
    {
        // Debug start
        \Illuminate\Support\Facades\Log::info('Loading doctor profile', [
            'doctor_id' => $id,
            'time' => now()->format('Y-m-d H:i:s')
        ]);

        // Find the doctor by ID with related data
        $doctor = Doctor::with(['user', 'ratings' => function($query) {
            $query->where('status', 'approved')
                  ->whereNotNull('doctor_rating');
        }, 'schedules'])->findOrFail($id);

        // Debug doctor loaded
        \Illuminate\Support\Facades\Log::info('Doctor loaded', [
            'doctor_id' => $id,
            'schedule_count' => $doctor->schedules->count(),
            'has_schedules' => $doctor->schedules->count() > 0
        ]);

        // Calculate average rating - using the accessor from the Doctor model
        $doctor->avgRating = $doctor->getAverageRatingAttribute() ?? 0;
        $doctor->ratingCount = $doctor->ratings->count();

        // Get recent reviews (latest 5)
        $recentReviews = $doctor->ratings()
            ->where('status', 'approved')
            ->whereNotNull('doctor_rating')
            ->with(['user' => function($query) {
                $query->withDefault([
                    'FullName' => 'Anonymous User',
                    'Email' => 'anonymous@example.com'
                ]);
            }])
            ->latest()
            ->limit(5)
            ->get();

        // Calculate detailed rating stats
        $ratingAvgByCategory = [
            'service' => $doctor->ratings()->where('status', 'approved')->whereNotNull('service_rating')->avg('service_rating') ?? 0,
            'cleanliness' => $doctor->ratings()->where('status', 'approved')->whereNotNull('cleanliness_rating')->avg('cleanliness_rating') ?? 0,
            'staff' => $doctor->ratings()->where('status', 'approved')->whereNotNull('staff_rating')->avg('staff_rating') ?? 0,
            'wait_time' => $doctor->ratings()->where('status', 'approved')->whereNotNull('wait_time_rating')->avg('wait_time_rating') ?? 0,
        ];

        $ratingDistribution = [
            5 => $doctor->ratings()->where('status', 'approved')->where('doctor_rating', 5)->count(),
            4 => $doctor->ratings()->where('status', 'approved')->where('doctor_rating', 4)->count(),
            3 => $doctor->ratings()->where('status', 'approved')->where('doctor_rating', 3)->count(),
            2 => $doctor->ratings()->where('status', 'approved')->where('doctor_rating', 2)->count(),
            1 => $doctor->ratings()->where('status', 'approved')->where('doctor_rating', 1)->count(),
        ];

        // Get the doctor's upcoming appointments to show availability
        $upcomingAppointments = $doctor->appointments()
            ->where('AppointmentDate', '>=', now()->format('Y-m-d'))
            ->orderBy('AppointmentDate')
            ->orderBy('AppointmentTime')
            ->get()
            ->groupBy('AppointmentDate');

        // Get the next 7 days for date selection
        $dates = [];
        $today = \Carbon\Carbon::today();

        for ($i = 0; $i < 7; $i++) {
            $date = $today->copy()->addDays($i);
            $dateEntry = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('d'),
                'month' => $date->format('m'),
                'year' => $date->format('Y'),
                'day_name' => $date->format('l'),
                'day_name_short' => $date->format('D'),
                'day_of_week' => $date->dayOfWeek == 0 ? 7 : $date->dayOfWeek, // Convert Sunday from 0 to 7
            ];

            // Debug date entry creation
            \Illuminate\Support\Facades\Log::info('Creating date entry', [
                'date' => $dateEntry['date'],
                'day_name' => $dateEntry['day_name'],
                'carbon_day_of_week' => $date->dayOfWeek,
                'converted_day_of_week' => $dateEntry['day_of_week']
            ]);

            $dates[] = $dateEntry;
        }

        // Debug all doctor schedules
        \Illuminate\Support\Facades\Log::info('All schedules for doctor', [
            'doctor_id' => $id,
            'schedules' => $doctor->schedules
        ]);

        // Get available time slots for each day
        $timeSlots = [];
        $hasSchedule = false;
        $totalSlots = 0;
        $firstDayWithSlots = null;

        foreach ($dates as $date) {
            $dateString = $date['date'];
            $dayOfWeek = $date['day_of_week'];

            // Check if doctor has a schedule for this day of week
            $scheduleForThisDay = $doctor->schedules()
                ->where('day_of_week', $dayOfWeek)
                ->where('is_active', true)
                ->first();

            // Debug schedule check
            \Illuminate\Support\Facades\Log::info('Schedule for day ' . $dayOfWeek, [
                'date' => $dateString,
                'found_schedule' => $scheduleForThisDay ? true : false,
                'schedule_id' => $scheduleForThisDay ? $scheduleForThisDay->id : null,
                'start_time' => $scheduleForThisDay ? $scheduleForThisDay->start_time : null,
                'end_time' => $scheduleForThisDay ? $scheduleForThisDay->end_time : null
            ]);

            if (!$scheduleForThisDay) {
                // No schedule for this day
                \Illuminate\Support\Facades\Log::info('No schedule for ' . $dateString);
                $timeSlots[$dateString] = [];
                continue;
            }

            // Got a schedule - generate time slots
            $hasSchedule = true;

            // Get already booked appointments
            $bookedAppointments = $doctor->appointments()
                ->where('AppointmentDate', $dateString)
                ->get();

            $bookedTimes = $bookedAppointments->pluck('AppointmentTime')->toArray();

            \Illuminate\Support\Facades\Log::info('Booked appointments for ' . $dateString, [
                'booked_times' => $bookedTimes
            ]);

            // Generate time slots based on schedule
            $startTime = \Carbon\Carbon::parse($scheduleForThisDay->start_time);
            $endTime = \Carbon\Carbon::parse($scheduleForThisDay->end_time);
            $slots = [];

            \Illuminate\Support\Facades\Log::info('Generating slots from ' . $startTime->format('H:i') . ' to ' . $endTime->format('H:i'), [
                'date' => $dateString,
                'start' => $startTime->format('H:i'),
                'end' => $endTime->format('H:i')
            ]);

            $slotTime = clone $startTime;
            while ($slotTime < $endTime) {
                $virtualTimeSlot = new \App\Models\DoctorTimeSlot();
                $virtualTimeSlot->doctor_id = $id;
                $virtualTimeSlot->date = $dateString;
                $virtualTimeSlot->time = $slotTime->format('H:i:s');

                // Check if this time is already booked
                $isBooked = in_array($slotTime->format('H:i:s'), $bookedTimes);
                $virtualTimeSlot->status = $isBooked ? 'booked' : 'available';

                // Create slot ID with proper format: slot_YYYYMMDDHHMMSS
                $slotId = sprintf(
                    'slot_%s%s%s%s%s%s',
                    $dateString,
                    $slotTime->format('H'),
                    $slotTime->format('i'),
                    $slotTime->format('s'),
                    '00', // Add milliseconds for uniqueness
                    rand(100, 999) // Add random number for uniqueness
                );
                $virtualTimeSlot->id = $slotId;

                \Illuminate\Support\Facades\Log::info('Created virtual slot', [
                    'slot_id' => $slotId,
                    'date' => $dateString,
                    'time' => $slotTime->format('H:i:s'),
                    'status' => $virtualTimeSlot->status
                ]);

                $slots[] = $virtualTimeSlot;
                $slotTime->addMinutes(30);
            }

            $timeSlots[$dateString] = $slots;
            $totalSlots += count($slots);

            if (!$firstDayWithSlots && count($slots) > 0) {
                $firstDayWithSlots = $dateString;
            }

            \Illuminate\Support\Facades\Log::info('Generated slots for ' . $dateString, [
                'count' => count($slots),
                'first_slot' => count($slots) > 0 ? $slots[0]->time : null,
                'last_slot' => count($slots) > 0 ? $slots[count($slots) - 1]->time : null
            ]);
        }

        // Log the final result
        \Illuminate\Support\Facades\Log::info('Finished loading time slots', [
            'doctor_id' => $id,
            'has_schedule' => $hasSchedule,
            'total_slots' => $totalSlots,
            'first_day_with_slots' => $firstDayWithSlots,
            'total_dates' => count($dates),
            'slots_by_date' => collect($timeSlots)->map(function($dateSlots) {
                return count($dateSlots);
            })
        ]);

        return view('pages.doctor_profile', [
            'doctor' => $doctor,
            'recentReviews' => $recentReviews,
            'upcomingAppointments' => $upcomingAppointments,
            'dates' => $dates,
            'timeSlots' => $timeSlots,
            'hasSchedule' => $hasSchedule,
            'ratingAvgByCategory' => $ratingAvgByCategory,
            'ratingDistribution' => $ratingDistribution,
            'debug' => [
                'totalDates' => count($dates),
                'totalSlots' => $totalSlots,
                'firstDayWithSlots' => $firstDayWithSlots
            ]
        ]);
    }

    /**
     * Display a listing of the user's prescriptions
     */
    public function userPrescriptions()
    {
        $userId = Auth::id();

        // Get all prescriptions for the authenticated user
        $prescriptions = \App\Models\Prescription::with(['prescriptionDetail', 'prescriptionDetail.medicine', 'doctor.user'])
            ->where('UserID', $userId)
            ->orderByDesc('PrescriptionDate')
            ->get();

        return view('pages.user.prescriptions', [
            'prescriptions' => $prescriptions
        ]);
    }

    /**
     * Display a specific prescription
     */
    public function prescriptionShow($id)
    {
        $userId = Auth::id();

        // Get the specific prescription
        $prescription = \App\Models\Prescription::with(['prescriptionDetail', 'prescriptionDetail.medicine', 'user', 'doctor.user'])
            ->where('PrescriptionID', $id)
            ->where('UserID', $userId) // Ensure the prescription belongs to the authenticated user
            ->firstOrFail();

        // Get the doctor who created the prescription
        $doctor = $prescription->doctor->user;

        return view('pages.user.prescription_detail', [
            'prescription' => $prescription,
            'doctor' => $doctor
        ]);
    }

    /**
     * Generate and download a PDF for a prescription
     */
    public function downloadPrescriptionPdf($id)
    {
        $userId = Auth::id();

        // Get the specific prescription
        $prescription = \App\Models\Prescription::with(['prescriptionDetail', 'prescriptionDetail.medicine', 'user', 'doctor.user'])
            ->where('PrescriptionID', $id)
            ->where('UserID', $userId) // Ensure the prescription belongs to the authenticated user
            ->firstOrFail();

        // Get the doctor who created the prescription
        $doctor = $prescription->doctor;

        // Cấu hình dompdf
        $options = new \Dompdf\Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);

        // Khởi tạo Dompdf
        $dompdf = new \Dompdf\Dompdf($options);

        // Render view thành HTML
        $html = view('doctor.prescription_pdf_full', [
            'prescription' => $prescription,
            'doctor' => $doctor,
        ])->render();

        // Load HTML vào Dompdf
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Tạo file PDF và tải xuống
        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="don_thuoc_'.$id.'.pdf"',
        ]);
    }

    /**
     * Display the user profile page
     */
    public function userProfile()
    {
        $user = Auth::user();

        // Check if user has a doctor profile
        $doctorProfile = null;
        if ($user->RoleID === 'doctor') {
            $doctorProfile = Doctor::where('UserID', $user->UserID)->first();
        }

        return view('pages.user.profile', [
            'user' => $user,
            'doctorProfile' => $doctorProfile
        ]);
    }

    /**
     * Update user profile information
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validate the request data
        $validated = $request->validate([
            'FullName' => 'required|string|max:100',
            'Email' => 'required|email|unique:users,Email,' . $user->UserID . ',UserID',
            'PhoneNumber' => 'required|string|max:15',
            'DateOfBirth' => 'nullable|date',
            'Gender' => 'nullable|string|in:Male,Female,Other',
            'Address' => 'nullable|string',
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // If password is being updated, verify the current password
        if ($request->filled('current_password') && $request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng'])->withInput();
            }
        }

        // Update the user info with the fillable fields
        $updateData = [
            'FullName' => $validated['FullName'],
            'Email' => $validated['Email'],
            'PhoneNumber' => $validated['PhoneNumber'],
            'DateOfBirth' => $validated['DateOfBirth'] ?? null,
            'Gender' => $validated['Gender'] ?? null,
            'Address' => $validated['Address'] ?? null,
        ];

        // Update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        // Use the update method with the fillable fields
        User::where('UserID', $user->UserID)->update($updateData);

        return redirect()->route('users.profile')->with('success', 'Thông tin cá nhân đã được cập nhật thành công');
    }
}
// php artisan make:controller HomeController
