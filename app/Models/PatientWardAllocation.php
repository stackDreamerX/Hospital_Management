<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientWardAllocation extends Model
{
    use HasFactory;

    protected $primaryKey = 'AllocationID';
    protected $fillable = [
        'PatientID',
        'WardBedID',
        'AllocationDate',
        'DischargeDate',
        'Notes',
        'AllocatedByUserID',
    ];

    protected $casts = [
        'AllocationDate' => 'datetime',
        'DischargeDate' => 'datetime',
    ];

    /**
     * Get the patient that owns the allocation
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'PatientID', 'PatientID');
    }

    /**
     * Get the bed that is allocated
     */
    public function wardBed()
    {
        return $this->belongsTo(WardBed::class, 'WardBedID', 'WardBedID');
    }

    /**
     * Get the user who allocated the bed
     */
    public function allocatedBy()
    {
        return $this->belongsTo(User::class, 'AllocatedByUserID', 'id');
    }

    /**
     * Scope a query to only include active allocations
     */
    public function scopeActive($query)
    {
        return $query->whereNull('DischargeDate');
    }

    /**
     * Scope a query to only include discharged allocations
     */
    public function scopeDischarged($query)
    {
        return $query->whereNotNull('DischargeDate');
    }
} 