<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DoctorSchedule;
use App\Models\DoctorTimeSlot;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index()
    {
        $doctor = Doctor::where('UserID', Auth::id())->first();

        if (!$doctor) {
            return redirect()->back()->with('error', 'Doctor profile not found');
        }

        $schedules = $doctor->schedules()->get();

        $weekdays = [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            7 => 'Sunday',
        ];

        // Generate time slots for display (7:00 AM to 7:00 PM in 30-minute intervals)
        $timeSlots = [];
        $startTime = Carbon::createFromTime(7, 0, 0);
        $endTime = Carbon::createFromTime(19, 0, 0);

        while ($startTime < $endTime) {
            $timeSlots[] = $startTime->format('H:i');
            $startTime->addMinutes(30);
        }

        return view('doctor.schedule', compact('schedules', 'weekdays', 'timeSlots', 'doctor'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'day_of_week' => 'required|integer|min:1|max:7',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $doctor = Doctor::where('UserID', Auth::id())->first();

        if (!$doctor) {
            return response()->json(['error' => 'Doctor profile not found'], 404);
        }

        // Log the request data
        \Illuminate\Support\Facades\Log::info('Doctor schedule update request', [
            'doctor_id' => $doctor->DoctorID,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time
        ]);

        // Create or update schedule
        $schedule = DoctorSchedule::updateOrCreate(
            [
                'doctor_id' => $doctor->DoctorID,
                'day_of_week' => $request->day_of_week,
            ],
            [
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'is_active' => true,
            ]
        );

        // Generate time slots for upcoming dates
        try {
            $this->generateTimeSlots($doctor->DoctorID, $request->day_of_week, $request->start_time, $request->end_time);

            // Log success message
            \Illuminate\Support\Facades\Log::info('Time slots generated successfully', [
                'doctor_id' => $doctor->DoctorID,
                'day_of_week' => $request->day_of_week,
                'schedule_id' => $schedule->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Schedule updated successfully. Time slots have been created for the next 4 weeks.'
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error generating time slots', [
                'doctor_id' => $doctor->DoctorID,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Schedule updated but there was an issue generating time slots: ' . $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        $schedule = DoctorSchedule::findOrFail($id);
        $doctor = Doctor::where('UserID', Auth::id())->first();

        if (!$doctor || $schedule->doctor_id != $doctor->DoctorID) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $schedule->delete();

        return response()->json(['success' => true, 'message' => 'Schedule deleted successfully']);
    }

    public function generateTimeSlots($doctorId, $dayOfWeek, $startTime, $endTime)
    {
        // Generate time slots for this schedule for the next 4 weeks
        $today = Carbon::today();
        $currentDay = $today->dayOfWeek == 0 ? 7 : $today->dayOfWeek; // Convert Sunday from 0 to 7

        // Log the input parameters
        \Illuminate\Support\Facades\Log::info("Generating time slots", [
            'doctor_id' => $doctorId,
            'day_of_week' => $dayOfWeek,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'today' => $today->format('Y-m-d'),
            'current_day' => $currentDay
        ]);

        // Find the next occurrence of the selected day
        $daysToAdd = ($dayOfWeek - $currentDay + 7) % 7;
        if ($daysToAdd == 0) $daysToAdd = 7; // If it's the same day, go to next week

        // Changed daysToAdd calculation - include same day if it's early enough
        // If it's the same day and we're setting a schedule before current time, use next week
        if ($daysToAdd == 0 && Carbon::now()->format('H:i:s') > $startTime) {
            $daysToAdd = 7;
        }

        $nextDate = $today->copy()->addDays($daysToAdd);
        \Illuminate\Support\Facades\Log::info("Next date calculation", [
            'days_to_add' => $daysToAdd,
            'next_date' => $nextDate->format('Y-m-d')
        ]);

        // Generate for 4 weeks
        for ($week = 0; $week < 4; $week++) {
            $date = $nextDate->copy()->addWeeks($week)->format('Y-m-d');

            // Create 30-minute interval slots
            $start = Carbon::parse($date . ' ' . $startTime);
            $end = Carbon::parse($date . ' ' . $endTime);

            // Log for each week
            \Illuminate\Support\Facades\Log::info("Generating week {$week} slots", [
                'date' => $date,
                'start' => $start->format('Y-m-d H:i:s'),
                'end' => $end->format('Y-m-d H:i:s')
            ]);

            $slotCount = 0;
            while ($start < $end) {
                DoctorTimeSlot::updateOrCreate(
                    [
                        'doctor_id' => $doctorId,
                        'date' => $date,
                        'time' => $start->format('H:i:s')
                    ],
                    [
                        'status' => 'available',
                    ]
                );
                $slotCount++;
                $start->addMinutes(30);
            }

            \Illuminate\Support\Facades\Log::info("Created {$slotCount} slots for {$date}");
        }
    }
}