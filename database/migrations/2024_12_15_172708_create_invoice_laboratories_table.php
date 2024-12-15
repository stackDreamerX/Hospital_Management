<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoice_laboratories', function (Blueprint $table) {
            $table->id('InvoiceLaboratoryID'); // Primary Key
            $table->foreignId('InvoiceID')->constrained('invoices', 'InvoiceID')->onDelete('cascade');
            $table->foreignId('LaboratoryID')->constrained('laboratories', 'LaboratoryID')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_laboratories');
    }
};
