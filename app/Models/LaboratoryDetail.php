<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratoryDetail extends Model
{
    use HasFactory;

    protected $table = 'laboratory_details';
    protected $primaryKey = 'LaboratoryDetailID';
    protected $fillable = ['LaboratoryID', 'Price'];

    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class, 'LaboratoryID', 'LaboratoryID');
    }
}
