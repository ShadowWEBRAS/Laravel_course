<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'author' => $this->faker->name(),
            'description' => $this->faker->paragraph(3),
            'year' => $this->faker->numberBetween(1500, date('Y')),
            'available_copies' => $this->faker->numberBetween(1, 10),
        ];
    }
}
