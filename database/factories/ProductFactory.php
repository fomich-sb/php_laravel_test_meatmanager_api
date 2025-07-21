<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition()
    {
        $categories = ['Мясо', 'Птица', 'Колбасы', 'Полуфабрикаты'];
        
        return [
            'name' => $this->faker->unique()->words(3, true),
            'description' => $this->faker->sentence(10),
            'price' => $this->faker->randomFloat(2, 50, 2000),
            'category' => $this->faker->randomElement($categories),
            'stock' => $this->faker->numberBetween(0, 100),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}