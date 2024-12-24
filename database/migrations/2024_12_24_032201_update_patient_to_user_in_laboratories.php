<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePatientToUserInLaboratories extends Migration
{
    public function up()
    {
        Schema::table('laboratories', function (Blueprint $table) {
            $table->renameColumn('PatientID', 'UserID'); // Đổi tên cột
        });
    }

    public function down()
    {
        Schema::table('laboratories', function (Blueprint $table) {
            $table->renameColumn('UserID', 'PatientID'); // Khôi phục nếu rollback
        });
    }
}

