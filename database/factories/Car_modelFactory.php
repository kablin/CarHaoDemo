<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car_model>
 */
class Car_modelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $brands =  \App\Models\Brand::pluck('id')->toArray();
        return [
            'title' => fake('ru_RU')->unique()->word(),
            'brand_id' => fake()->randomElement($brands),
        ];
    }
}
