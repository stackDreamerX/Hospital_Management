<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $table = 'patients';
    protected $primaryKey = 'PatientID';
    protected $fillable = ['UserID', 'DateOfBirth', 'Gender', 'Address'];

    public function laboratories()
    {
        return $this->hasMany(Laboratory::class, 'PatientID', 'PatientID');
    }

    /**
     * Get all bed allocations for this patient
     */
    public function bedAllocations()
    {
        return $this->hasMany(PatientWardAllocation::class, 'PatientID', 'PatientID');
    }

    /**
     * Get the current active bed allocation for this patient
     */
    public function currentBedAllocation()
    {
        return $this->hasOne(PatientWardAllocation::class, 'PatientID', 'PatientID')
            ->whereNull('DischargeDate');
    }

    /**
     * Get bed history records for this patient
     */
    public function bedHistory()
    {
        return $this->hasMany(WardBedHistory::class, 'PatientID', 'PatientID');
    }
}
