<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    use HasFactory;

    // Đặt tên khóa chính
    protected $primaryKey = 'TreatmentID';

    // Cấu hình tự tăng khóa chính
    public $incrementing = true;

    // Kiểu dữ liệu của khóa chính
    protected $keyType = 'int';

    // Các cột có thể được gán giá trị hàng loạt
    protected $fillable = [
        'TreatmentTypeID',
        'TreatmentDate',
        'UserID', // Liên kết với bảng users
        'DoctorID', // Liên kết với bảng doctors
        'TotalPrice',
    ];

    // Liên kết tới bảng treatment_types
    public function treatmentType()
    {
        return $this->belongsTo(TreatmentType::class, 'TreatmentTypeID', 'TreatmentTypeID');
    }

    // Liên kết tới bảng users (bệnh nhân)
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }

    // Liên kết tới bảng doctors
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'DoctorID', 'DoctorID');
    }
}
