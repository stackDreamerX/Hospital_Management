<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medicine;

class MedicineSeeder extends Seeder
{
    public function run()
    {
        // Tạo 20 bản ghi dữ liệu giả
        Medicine::factory()->count(15)->create();
    }
}
