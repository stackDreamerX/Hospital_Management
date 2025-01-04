<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LaboratoryType;

class LaboratoryTypeSeeder extends Seeder
{
    public function run()
    {
        LaboratoryType::factory(10)->create(); // Tạo 10 loại phòng thí nghiệm mẫu
    }
}
