<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Car;

class CarsSeeder extends Seeder
{
    public function run(): void
    {
        $cars = [
            [
                'admin_id' => 1,
                'user_id' => 1,
                'car_name' => 'Aurora GT',
                'brand' => 'Aurora',
                'model' => 'GT',
                'year' => 2023,
                'body_type' => 'Coupe',
                'fuel_type' => 'Petrol',
                'engine_power' => 420,
                'transmission' => 'Automatic',
                'top_speed' => 300,
                'acceleration' => 3.6,
                'description' => 'A sleek grand tourer with elegant lines and powerful performance.',
                'image_url' => 'https://images.unsplash.com/photo-1549921296-3a6a0bcbf7b6?w=1200&q=80&auto=format&fit=crop',
                'price' => 89999.99,
            ],
            [
                'admin_id' => 1,
                'user_id' => 1,
                'car_name' => 'Vanguard R',
                'brand' => 'Vanguard',
                'model' => 'R',
                'year' => 2022,
                'body_type' => 'Sedan',
                'fuel_type' => 'Petrol',
                'engine_power' => 280,
                'transmission' => 'Automatic',
                'top_speed' => 250,
                'acceleration' => 5.8,
                'description' => 'A refined performance sedan with balanced handling and luxury touches.',
                'image_url' => 'https://images.unsplash.com/photo-1510797215324-95aa89f7a5c6?w=1200&q=80&auto=format&fit=crop',
                'price' => 42999.00,
            ],
            [
                'admin_id' => 1,
                'user_id' => 1,
                'car_name' => 'Nimbus LX',
                'brand' => 'Nimbus',
                'model' => 'LX',
                'year' => 2021,
                'body_type' => 'Hatchback',
                'fuel_type' => 'Petrol',
                'engine_power' => 150,
                'transmission' => 'Manual',
                'top_speed' => 200,
                'acceleration' => 8.9,
                'description' => 'A practical hatch with comfortable cabin and efficient power.',
                'image_url' => 'https://images.unsplash.com/photo-1525609004556-c46c7d6cf023?w=1200&q=80&auto=format&fit=crop',
                'price' => 19999.00,
            ],
            [
                'admin_id' => 1,
                'user_id' => 1,
                'car_name' => 'Comet GT',
                'brand' => 'Comet',
                'model' => 'GT',
                'year' => 2020,
                'body_type' => 'Coupe',
                'fuel_type' => 'Petrol',
                'engine_power' => 360,
                'transmission' => 'Automatic',
                'top_speed' => 290,
                'acceleration' => 4.1,
                'description' => 'A driver-focused coupe with sharp responses and modern styling.',
                'image_url' => 'https://images.unsplash.com/photo-1493238792000-8113da705763?w=1200&q=80&auto=format&fit=crop',
                'price' => 57999.00,
            ],
            [
                'admin_id' => 1,
                'user_id' => 1,
                'car_name' => 'Solstice Sport',
                'brand' => 'Solstice',
                'model' => 'Sport',
                'year' => 2024,
                'body_type' => 'Convertible',
                'fuel_type' => 'Petrol',
                'engine_power' => 330,
                'transmission' => 'Manual',
                'top_speed' => 270,
                'acceleration' => 4.5,
                'description' => 'An open-top roadster combining fun and refinement for weekend drives.',
                'image_url' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=1200&q=80&auto=format&fit=crop',
                'price' => 65999.00,
            ],
            [
                'admin_id' => 1,
                'user_id' => 1,
                'car_name' => 'Atlas EV',
                'brand' => 'Atlas',
                'model' => 'EV',
                'year' => 2025,
                'body_type' => 'SUV',
                'fuel_type' => 'Electric',
                'engine_power' => 300,
                'transmission' => 'Single-speed',
                'top_speed' => 220,
                'acceleration' => 5.2,
                'description' => 'A modern electric SUV with practical range and sophisticated interior.',
                'image_url' => 'https://images.unsplash.com/photo-1542362567-b07e54358753?w=1200&q=80&auto=format&fit=crop',
                'price' => 74999.50,
            ],
        ];

        foreach ($cars as $c) {
            Car::updateOrCreate([
                'car_name' => $c['car_name'],
                'brand' => $c['brand'],
            ], $c);
        }
    }
}
