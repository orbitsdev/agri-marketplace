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
            ['name' => 'Vegetables', 'description' => 'Fresh and organic vegetables'],
            ['name' => 'Fruits', 'description' => 'Sweet and fresh fruits'],
            ['name' => 'Dairy', 'description' => 'Milk, cheese, and other dairy products'],
            ['name' => 'Nuts', 'description' => 'Healthy and nutritious nuts'],
            ['name' => 'Grains', 'description' => 'Cereals, wheat, and other grains'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
