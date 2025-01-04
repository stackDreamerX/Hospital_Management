<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Medicine>
 */
class MedicineFactory extends Factory
{
    public function definition()
    {
        return [
            'MedicineName' => $this->faker->word(),
            'ExpiryDate' => $this->faker->dateTimeBetween('+1 year', '+2 years'),
            'ManufacturingDate' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'UnitPrice' => $this->faker->numberBetween(5, 500),
        ];
    }
}
