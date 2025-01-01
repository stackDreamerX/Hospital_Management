<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceLaboratory extends Model
{
    use HasFactory;

    protected $primaryKey = 'InvoiceLaboratoryID';
    protected $fillable = [
        'InvoiceID',
        'LaboratoryID',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'InvoiceID', 'InvoiceID');
    }

    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class, 'LaboratoryID', 'LaboratoryID');
    }
}
