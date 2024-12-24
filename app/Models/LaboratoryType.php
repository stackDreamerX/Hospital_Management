<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratoryType extends Model
{
    use HasFactory;

    // Tên bảng trong cơ sở dữ liệu
    protected $table = 'laboratory_types';

    // Khóa chính của bảng
    protected $primaryKey = 'LaboratoryTypeID';

    // Các trường có thể gán giá trị
    protected $fillable = [
        'LaboratoryTypeName',
        'description',
        'price',
    ];

    // Định nghĩa quan hệ với bảng Laboratory
    public function laboratories()
    {
        return $this->hasMany(Laboratory::class, 'LaboratoryTypeID', 'LaboratoryTypeID');
    }
}
