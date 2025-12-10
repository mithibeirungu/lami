<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Brand;
use App\Models\BodyType;
use App\Models\CarImage;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    public function run()
    {
        $overseer = \App\Models\User::where('role', 'overseer')->first();

        $cars = [
            [
                'title' => 'Aurora GT',
                'brand_id' => Brand::where('name', 'Luxor')->first()->brand_id,
                'model' => 'Phantom',
                'year' => 2024,
                'body_type_id' => BodyType::where('name', 'Coupe')->first()->body_type_id,
                'transmission' => 'Automatic',
                'fuel_type' => 'Premium Gasoline',
                'engine_type' => 'V12',
                'engine_size' => '6.0L',
                'horsepower' => 625,
                'torque' => 568,
                'seating_capacity' => 2,
                'drive_type' => 'RWD',
                'description' => 'A stunning two-seater grand tourer with cutting-edge performance.',
                'thumbnail_image' => 'https://images.unsplash.com/photo-1511914265872-c40672576bbc?auto=format&fit=crop&w=1200&q=60',
                'price' => 185000,
                'created_by' => $overseer->user_id,
            ],
            [
                'title' => 'Vanguard R',
                'brand_id' => Brand::where('name', 'Zenith')->first()->brand_id,
                'model' => 'Titan',
                'year' => 2023,
                'body_type_id' => BodyType::where('name', 'SUV')->first()->body_type_id,
                'transmission' => 'Automatic',
                'fuel_type' => 'Diesel',
                'engine_type' => 'V8 Turbo',
                'engine_size' => '4.8L',
                'horsepower' => 540,
                'torque' => 630,
                'seating_capacity' => 7,
                'drive_type' => 'AWD',
                'description' => 'A robust performance SUV designed for adventure and style.',
                'thumbnail_image' => 'https://images.unsplash.com/photo-1605559424843-9e4c3ca4628a?auto=format&fit=crop&w=1200&q=60',
                'price' => 95000,
                'created_by' => $overseer->user_id,
            ],
            [
                'title' => 'Nimbus LX',
                'brand_id' => Brand::where('name', 'Pinnacle')->first()->brand_id,
                'model' => 'Luxury',
                'year' => 2024,
                'body_type_id' => BodyType::where('name', 'Sedan')->first()->body_type_id,
                'transmission' => 'Automatic',
                'fuel_type' => 'Premium Gasoline',
                'engine_type' => 'V8',
                'engine_size' => '5.0L',
                'horsepower' => 480,
                'torque' => 500,
                'seating_capacity' => 5,
                'drive_type' => 'RWD',
                'description' => 'The ultimate expression of elegance and refined engineering.',
                'thumbnail_image' => 'https://images.unsplash.com/photo-1550355291-bbee04a92027?auto=format&fit=crop&w=1200&q=60',
                'price' => 120000,
                'created_by' => $overseer->user_id,
            ],
        ];

        foreach ($cars as $carData) {
            $car = Car::create($carData);

            // Add a primary image
            CarImage::create([
                'car_id' => $car->car_id,
                'category_id' => null,
                'image_url' => $carData['thumbnail_image'],
                'title' => 'Main Image',
                'is_primary' => true,
            ]);
        }
    }
}
