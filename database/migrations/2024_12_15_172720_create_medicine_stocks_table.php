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
        Schema::create('medicine_stock', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('MedicineID')->constrained('medicines', 'MedicineID')->onDelete('cascade');
            $table->integer('QuantityInStock');
            $table->string('Provider', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_stock');
    }
};
