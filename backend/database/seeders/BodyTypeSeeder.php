<?php

namespace Database\Seeders;

use App\Models\BodyType;
use Illuminate\Database\Seeder;

class BodyTypeSeeder extends Seeder
{
    public function run()
    {
        $types = [
            'Sedan',
            'SUV',
            'Coupe',
            'Hatchback',
            'Wagon',
            'Convertible',
            'Pickup',
            'Minivan',
        ];

        foreach ($types as $type) {
            BodyType::create(['name' => $type]);
        }
    }
}
