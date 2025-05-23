<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientMonitoring extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $fillable = [
        'AllocationID',
        'PatientID',
        'blood_pressure',
        'heart_rate',
        'temperature',
        'spo2',
        'treatment_outcome',
        'notes',
        'doctor_id',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    /**
     * Get the allocation that owns this monitoring record
     */
    public function allocation()
    {
        return $this->belongsTo(PatientWardAllocation::class, 'AllocationID', 'AllocationID');
    }

    /**
     * Get the patient being monitored
     */
    public function patient()
    {
        return $this->belongsTo(User::class, 'PatientID', 'UserID');
    }

    /**
     * Get the doctor who recorded this monitoring
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id', 'UserID');
    }
}