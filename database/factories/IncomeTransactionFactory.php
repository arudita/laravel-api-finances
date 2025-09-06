<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class IncomeTransactionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->numberBetween(0,1),
            'archived' => $this->faker->numberBetween(0,1),
            'amount' => $this->faker->randomDigit(),
            'payment_date' => $this->faker->date(),
        ];
    }
}
