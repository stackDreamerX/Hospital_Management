<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $fillable = [
        'AllocationID',
        'PatientID',
        'medication_name',
        'dosage',
        'frequency',
        'start_date',
        'end_date',
        'instructions',
        'doctor_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the allocation that owns this medication
     */
    public function allocation()
    {
        return $this->belongsTo(PatientWardAllocation::class, 'AllocationID', 'AllocationID');
    }

    /**
     * Get the patient receiving the medication
     */
    public function patient()
    {
        return $this->belongsTo(User::class, 'PatientID', 'UserID');
    }

    /**
     * Get the doctor who prescribed this medication
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id', 'UserID');
    }

    /**
     * Check if medication is currently active
     */
    public function isActive()
    {
        if (!$this->end_date) {
            return true;
        }

        return $this->end_date->greaterThanOrEqualTo(now()->startOfDay());
    }
}