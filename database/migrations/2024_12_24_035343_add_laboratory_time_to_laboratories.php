<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('laboratories', function (Blueprint $table) {
        $table->time('LaboratoryTime')->nullable(); // Thêm cột
    });
}

public function down()
{
    Schema::table('laboratories', function (Blueprint $table) {
        $table->dropColumn('LaboratoryTime'); // Xóa cột nếu rollback
    });
}

};
