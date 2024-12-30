<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentType extends Model
{
    use HasFactory;

    // Đặt tên khóa chính
    protected $primaryKey = 'TreatmentTypeID';

    // Cấu hình tự tăng khóa chính
    public $incrementing = true;

    // Kiểu dữ liệu của khóa chính
    protected $keyType = 'int';

    // Các cột có thể được gán giá trị hàng loạt
    protected $fillable = [
        'TreatmentTypeName',
    ];

    // Liên kết tới bảng treatments
    public function treatments()
    {
        return $this->hasMany(Treatment::class, 'TreatmentTypeID', 'TreatmentTypeID');
    }
}
