<?php

namespace Database\Seeders;

use App\Models\ImageCategory;
use Illuminate\Database\Seeder;

class ImageCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Exterior', 'description' => 'Exterior views of the car'],
            ['name' => 'Interior', 'description' => 'Interior shots of the cabin'],
            ['name' => 'Detail', 'description' => 'Close-up details'],
            ['name' => 'Engine', 'description' => 'Engine bay'],
        ];

        foreach ($categories as $cat) {
            ImageCategory::create($cat);
        }
    }
}
