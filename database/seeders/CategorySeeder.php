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
        $categories = [
            ['name' => 'Bread', 'description' => 'Freshly baked artisanal breads'],
            ['name' => 'Pastries', 'description' => 'Delicious sweet and savory pastries'],
            ['name' => 'Cakes', 'description' => 'Celebration and everyday cakes for all occasions'],
            ['name' => 'Cookies', 'description' => 'Homemade cookies and biscuits'],
            ['name' => 'Desserts', 'description' => 'Specialty desserts and sweet treats'],
            ['name' => 'Savory', 'description' => 'Savory baked goods and snacks'],
            ['name' => 'Gluten-Free', 'description' => 'Gluten-free bakery options'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
