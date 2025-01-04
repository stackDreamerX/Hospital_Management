<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LaboratoryTypeFactory extends Factory
{
    public function definition()
    {
        return [
            'LaboratoryTypeName' => $this->faker->unique()->word(), // Tên phòng thí nghiệm
            'price' => $this->faker->randomFloat(2, 10, 100), // Giá ngẫu nhiên từ 10 đến 100
            'description' => $this->faker->sentence(), // Mô tả ngẫu nhiên
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
