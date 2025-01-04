<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicineStock;

class MedicineStockSeeder extends Seeder
{
    public function run()
    {
        MedicineStock::factory(25)->create(); // Tạo 50 bản ghi mẫu
    }
}
