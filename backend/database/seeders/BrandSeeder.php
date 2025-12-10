<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run()
    {
        $brands = [
            ['name' => 'Luxor', 'country' => 'Italy', 'logo_url' => null],
            ['name' => 'Zenith', 'country' => 'Germany', 'logo_url' => null],
            ['name' => 'Pinnacle', 'country' => 'United States', 'logo_url' => null],
            ['name' => 'Eclipse', 'country' => 'Japan', 'logo_url' => null],
            ['name' => 'Nova', 'country' => 'Sweden', 'logo_url' => null],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
