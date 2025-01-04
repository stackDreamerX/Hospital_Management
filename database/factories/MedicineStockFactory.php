<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Medicine;

class MedicineStockFactory extends Factory
{
    public function definition()
    {
        return [
            'MedicineID' => Medicine::factory(), // Tạo medicine mẫu
            'Quantity' => $this->faker->numberBetween(10, 100), // Số lượng trong kho
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
