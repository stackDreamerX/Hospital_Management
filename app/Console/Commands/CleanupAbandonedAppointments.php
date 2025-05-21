<?php

namespace App\Console\Commands;

use App\Models\DoctorTimeSlot;
use App\Models\Appointment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanupAbandonedAppointments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up abandoned appointments and free up timeslots';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->cleanupAbandonedSlots();
        $this->cleanupPendingAppointments();

        $this->info('Appointment cleanup completed successfully.');
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

            $abandonedSlots = DoctorTimeSlot::where('status', 'booked')
                ->whereNull('appointment_id')
                ->where('updated_at', '<', $tenMinutesAgo)
                ->get();

            if ($abandonedSlots->count() > 0) {
                $this->info("Found {$abandonedSlots->count()} abandoned slots to cleanup");
                Log::info("Found {$abandonedSlots->count()} abandoned slots to cleanup");

                foreach ($abandonedSlots as $slot) {
                    $this->line("Freeing abandoned slot: ID {$slot->id}, Date: {$slot->date}, Time: {$slot->time}");
                    Log::info("Freeing abandoned slot", [
                        'slot_id' => $slot->id,
                        'date' => $slot->date,
                        'time' => $slot->time,
                        'updated_at' => $slot->updated_at
                    ]);

                    $slot->status = 'available';
                    $slot->save();
                }
            } else {
                $this->info("No abandoned slots found.");
            }
        } catch (\Exception $e) {
            $this->error("Error cleaning up abandoned slots: " . $e->getMessage());
            Log::error("Error cleaning up abandoned slots: " . $e->getMessage());
        }
    }

    /**
     * Cleanup appointments that have payment_status=pending
     * and were created more than 10 minutes ago
     */
    private function cleanupPendingAppointments()
    {
        try {
            // Find appointments with pending payment status created more than 10 minutes ago
            $tenMinutesAgo = now()->subMinutes(10);

            $pendingAppointments = Appointment::where('payment_status', 'pending')
                ->where('created_at', '<', $tenMinutesAgo)
                ->get();

            if ($pendingAppointments->count() > 0) {
                $this->info("Found {$pendingAppointments->count()} pending appointments to cleanup");
                Log::info("Found {$pendingAppointments->count()} pending appointments to cleanup");

                foreach ($pendingAppointments as $appointment) {
                    $this->line("Deleting abandoned appointment: ID {$appointment->AppointmentID}");
                    Log::info("Deleting abandoned appointment", [
                        'appointment_id' => $appointment->AppointmentID,
                        'created_at' => $appointment->created_at
                    ]);

                    // Free up any associated timeslot
                    $slot = DoctorTimeSlot::where('appointment_id', $appointment->AppointmentID)->first();
                    if ($slot) {
                        $slot->status = 'available';
                        $slot->appointment_id = null;
                        $slot->save();

                        $this->line("Freed up associated timeslot: ID {$slot->id}");
                        Log::info("Freed up associated timeslot", [
                            'slot_id' => $slot->id,
                            'appointment_id' => $appointment->AppointmentID
                        ]);
                    }

                    // Delete the appointment
                    $appointment->delete();
                }
            } else {
                $this->info("No pending appointments found to cleanup.");
            }
        } catch (\Exception $e) {
            $this->error("Error cleaning up pending appointments: " . $e->getMessage());
            Log::error("Error cleaning up pending appointments: " . $e->getMessage());
        }
    }
}