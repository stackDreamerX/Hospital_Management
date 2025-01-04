<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactoryFake extends Factory
{
    public function definition()
    {
        return [
            'username' => $this->faker->unique()->userName(), // Tên người dùng duy nhất
            'password' => bcrypt('password'), // Mật khẩu mặc định
            'FullName' => $this->faker->name(), // Tên đầy đủ
            'Email' => $this->faker->unique()->safeEmail(), // Email duy nhất
            'PhoneNumber' => $this->faker->numerify('##########'), // Số điện thoại
            'RoleID' => $this->faker->randomElement(['doctor', 'patient']), // Vai trò (doctor/patient)
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
