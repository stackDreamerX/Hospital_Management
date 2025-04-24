<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WardBedHistory extends Model
{
    use HasFactory;

    protected $primaryKey = 'HistoryID';
    protected $fillable = [
        'WardBedID',
        'PatientID',
        'FromDate',
        'ToDate',
        'Status',
        'Note',
        'UpdatedByUserID',
    ];

    protected $casts = [
        'FromDate' => 'datetime',
        'ToDate' => 'datetime',
    ];

    /**
     * Get the bed that this history belongs to
     */
    public function wardBed()
    {
        return $this->belongsTo(WardBed::class, 'WardBedID', 'WardBedID');
    }

    /**
     * Get the patient associated with this history
     */
    public function patient()
    {
        return $this->belongsTo(User::class, 'PatientID', 'UserID');
    }

    /**
     * Get the user who updated the bed status
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'UpdatedByUserID', 'UserID');
    }

    /**
     * Scope a query to only include active history records
     */
    public function scopeActive($query)
    {
        return $query->whereNull('ToDate');
    }

    /**
     * Scope a query to only include completed history records
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('ToDate');
    }
} 