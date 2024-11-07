<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);



        $engine_type_items = [
            ['title' => 'бензин'],
            ['title' => 'дизель'],
            ['title' => 'гибрид'],
            ['title' => 'эдектричка'],

        ];

        foreach ($engine_type_items as $item) {
            \App\Models\Engine_type::query()->firstOrCreate(
                [
                    'title'          => $item['title'],
                ]
            );
        }


        $drive_type_items = [
            ['title' => 'передний'],
            ['title' => 'задний'],
            ['title' => 'полный'],
            ['title' => 'электричка'],

        ];


        foreach ($drive_type_items as $item) {
            \App\Models\Drive_type::query()->firstOrCreate(
                [
                    'title'          => $item['title'],
                ]
            );
        }



        \App\Models\Brand::factory(8)->create();
        \App\Models\Car_model::factory(40)->create();
        \App\Models\Car::factory(100)->create();

        \App\Models\Car::query()->get()->map(function ($car) {
            \App\Models\Image::factory()->create([
                'path' => fake()->imageUrl(),
                'car_id' => $car->id
            ]);
            \App\Models\Image::factory()->create([
                'path' => fake()->imageUrl(),
                'car_id' => $car->id
            ]);
            \App\Models\Image::factory()->create([
                'path' => fake()->imageUrl(),
                'car_id' => $car->id
            ]);

        });



    }
}
