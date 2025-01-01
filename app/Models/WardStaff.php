<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WardStaff extends Model
{
    use HasFactory;

    protected $primaryKey = 'WardStaffID';
    protected $fillable = [
        'WardID',
        'WardStaffName',
    ];

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'WardID', 'WardID');
    }
}

