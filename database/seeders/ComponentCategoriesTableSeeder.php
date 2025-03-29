<?php

namespace Database\Seeders;

use App\Models\ComponentCategory;
use Illuminate\Database\Seeder;

class ComponentCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'CPU',
                'description' => 'Central Processing Unit (Processor)',
            ],
            [
                'name' => 'GPU',
                'description' => 'Graphics Processing Unit (Video Card)',
            ],
            [
                'name' => 'RAM',
                'description' => 'Random Access Memory',
            ],
            [
                'name' => 'Motherboard',
                'description' => 'Main circuit board',
            ],
            [
                'name' => 'Storage',
                'description' => 'SSD, HDD, or NVMe storage devices',
            ],
            [
                'name' => 'Power Supply',
                'description' => 'Power Supply Unit (PSU)',
            ],
            [
                'name' => 'Cooling',
                'description' => 'CPU coolers, case fans, and liquid cooling systems',
            ],
            [
                'name' => 'Case',
                'description' => 'PC Case/Chassis',
            ],
            [
                'name' => 'Monitor',
                'description' => 'Display unit',
            ],
            [
                'name' => 'Keyboard',
                'description' => 'Input device for typing',
            ],
            [
                'name' => 'Mouse',
                'description' => 'Pointing device',
            ],
            [
                'name' => 'Headset',
                'description' => 'Audio headphones with microphone',
            ],
        ];

        foreach ($categories as $category) {
            ComponentCategory::create($category);
        }
    }
}