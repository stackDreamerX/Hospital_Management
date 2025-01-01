<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WardType extends Model
{
    use HasFactory;

    protected $primaryKey = 'WardTypeID';
    protected $fillable = [
        'TypeName',
        'Description',
    ];

    public function wards()
    {
        return $this->hasMany(Ward::class, 'WardTypeID', 'WardTypeID');
    }
}
