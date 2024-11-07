<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {


        $engines =  \App\Models\Engine_type::pluck('id')->toArray();
        $drives =  \App\Models\Drive_type::pluck('id')->toArray();
        $models =  \App\Models\Car_model::pluck('id')->toArray();

        return [
            'engine_type_id' => fake()->randomElement($engines),
            'drive_type_id' => fake()->randomElement($drives),
            'car_model_id' => fake()->randomElement($models),
            'price'=> fake()->numberBetween(499999,6000000),
            'manufactured_year'=> fake()->dateTimeBetween(),
        ];
    }
}
