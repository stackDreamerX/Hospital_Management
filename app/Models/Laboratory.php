<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
    
class Laboratory extends Model
{
    use HasFactory;

    protected $table = 'laboratories';
    protected $primaryKey = 'LaboratoryID';
    protected $fillable = [
        'LaboratoryTypeID',
        'UserID',
        'DoctorID',
        'LaboratoryDate',
        'LaboratoryTime',
        'TotalPrice',
    ];

    public function laboratoryType()
    {
        return $this->belongsTo(LaboratoryType::class, 'LaboratoryTypeID', 'LaboratoryTypeID');
    }
   
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID'); // Liên kết với bảng users
    }


    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'DoctorID', 'DoctorID');
    }

    public function laboratoryDetail()
    {
        return $this->hasMany(LaboratoryDetail::class, 'LaboratoryID', 'LaboratoryID');
    }

    public function laboratoryResult()
    {
        return $this->hasMany(LaboratoryResult::class, 'LaboratoryID', 'LaboratoryID');
    }
}
