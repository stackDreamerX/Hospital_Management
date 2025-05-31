<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

class PatientFactory extends Factory
{
    public function definition()
    {
        return [
            'UserID' => User::factory(), // Tạo user mẫu
            'DateOfBirth' => $this->faker->date(),
            'Gender' => $this->faker->randomElement(['Male', 'Female', 'Other']),
            'Address' => $this->faker->address(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
