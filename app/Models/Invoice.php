<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $primaryKey = 'InvoiceID';
    protected $fillable = [
        'InvoiceDate',
        'PatientID',
        'TotalPrice',
        'Status',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }

    public function invoiceTreatments()
    {
        return $this->hasMany(InvoiceTreatment::class, 'InvoiceID', 'InvoiceID');
    }

    public function invoiceLaboratories()
    {
        return $this->hasMany(InvoiceLaboratory::class, 'InvoiceID', 'InvoiceID');
    }

    public function invoicePrescriptions()
    {
        return $this->hasMany(InvoicePrescription::class, 'InvoiceID', 'InvoiceID');
    }
}
