<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratoryResult extends Model
{
    use HasFactory;

    protected $table = 'laboratory_results';
    protected $primaryKey = 'LaboratoryResultID';
    protected $fillable = ['LaboratoryID', 'Result'];

    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class, 'LaboratoryID', 'LaboratoryID');
    }
}
