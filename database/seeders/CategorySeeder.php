<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bakery categories preserved for reference:
        /*
        $categories = [
            ['name' => 'Bread', 'description' => 'Freshly baked artisanal breads'],
            ['name' => 'Pastries', 'description' => 'Delicious sweet and savory pastries'],
            ['name' => 'Cakes', 'description' => 'Celebration and everyday cakes for all occasions'],
            ['name' => 'Cookies', 'description' => 'Homemade cookies and biscuits'],
            ['name' => 'Desserts', 'description' => 'Specialty desserts and sweet treats'],
            ['name' => 'Savory', 'description' => 'Savory baked goods and snacks'],
            ['name' => 'Gluten-Free', 'description' => 'Gluten-free bakery options'],
        ];
        */
        // Agriculture-focused categories
        $categories = [
            ['name' => 'Vegetables', 'description' => 'Fresh farm-grown vegetables'],
            ['name' => 'Fruits', 'description' => 'Seasonal and tropical fruits'],
            ['name' => 'Dairy', 'description' => 'Milk, cheese, and other dairy products'],
            ['name' => 'Grains', 'description' => 'Rice, wheat, corn, and other grains'],
            ['name' => 'Livestock', 'description' => 'Meat, poultry, and eggs'],
            ['name' => 'Honey', 'description' => 'Pure, natural honey and bee products'],
            ['name' => 'Herbs & Spices', 'description' => 'Fresh and dried herbs and spices'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
