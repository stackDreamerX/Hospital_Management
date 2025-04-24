<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WardBed extends Model
{
    use HasFactory;

    protected $primaryKey = 'WardBedID';
    protected $fillable = [
        'WardID',
        'BedNumber',
        'Status',
    ];

    /**
     * Get the ward that the bed belongs to
     */
    public function ward()
    {
        return $this->belongsTo(Ward::class, 'WardID', 'WardID');
    }

    /**
     * Get the current patient allocation for this bed
     */
    public function currentAllocation()
    {
        return $this->hasOne(PatientWardAllocation::class, 'WardBedID', 'WardBedID')
            ->whereNull('DischargeDate');
    }

    /**
     * Get all allocations for this bed
     */
    public function allocations()
    {
        return $this->hasMany(PatientWardAllocation::class, 'WardBedID', 'WardBedID');
    }

    /**
     * Get all history records for this bed
     */
    public function history()
    {
        return $this->hasMany(WardBedHistory::class, 'WardBedID', 'WardBedID');
    }

    /**
     * Scope a query to only include available beds
     */
    public function scopeAvailable($query)
    {
        return $query->where('Status', 'available');
    }

    /**
     * Scope a query to only include occupied beds
     */
    public function scopeOccupied($query)
    {
        return $query->where('Status', 'occupied');
    }

    /**
     * Scope a query to only include beds under maintenance
     */
    public function scopeMaintenance($query)
    {
        return $query->where('Status', 'maintenance');
    }
} 