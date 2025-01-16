<?php

namespace Database\Seeders;

use App\Models\Farmer;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->command->error('No categories found. Please seed categories first.');
            return;
        }

        $farmer1 = Farmer::first();
        $farmer2 = Farmer::orderBy('id', 'desc')->first();

        if (!$farmer1 || !$farmer2) {
            $this->command->error('No farmers found. Please seed farmers first.');
            return;
        }

        $productsForFarmer1 = [
            [
                'product_name' => 'Organic Tomatoes',
                'description' => 'Organic tomatoes grown with natural farming techniques to ensure quality and freshness.',
                'short_description' => 'Natural organic tomatoes.',
                'quantity' => 100,
                'price' => 2.50,
            ],
            [
                'product_name' => 'Red Apples',
                'description' => 'Juicy, sweet, and hand-picked red apples ideal for snacking or cooking.',
                'short_description' => 'Fresh red apples.',
                'quantity' => 50,
                'price' => 3.00,
            ],
            [
                'product_name' => 'Golden Potatoes',
                'description' => 'Premium golden potatoes perfect for frying, baking, or boiling.',
                'short_description' => 'High-quality potatoes.',
                'quantity' => 200,
                'price' => 1.20,
            ],
        ];

        $productsForFarmer2 = [
            [
                'product_name' => 'Fresh Milk',
                'description' => 'Locally sourced fresh milk that’s creamy and full of flavor.',
                'short_description' => 'Fresh creamy milk.',
                'quantity' => 60,
                'price' => 1.50,
            ],
            [
                'product_name' => 'Free-range Eggs',
                'description' => 'Eggs collected from free-range hens raised on open pastures.',
                'short_description' => 'Pasture-raised eggs.',
                'quantity' => 100,
                'price' => 2.00,
            ],
            [
                'product_name' => 'Honey',
                'description' => 'Golden organic honey sourced from sustainable local apiaries.',
                'short_description' => 'Pure organic honey.',
                'quantity' => 40,
                'price' => 6.00,
            ],
        ];

        foreach ($productsForFarmer1 as $product) {
            Product::create([
                'farmer_id' => $farmer1->id,
                'category_id' => $categories->random()->id,
                'product_name' => $product['product_name'],
                'description' => $product['description'],
                'short_description' => $product['short_description'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'status' => 'Available',
                'is_published' => true,
            ]);
        }

        foreach ($productsForFarmer2 as $product) {
            Product::create([
                'farmer_id' => $farmer2->id,
                'category_id' => $categories->random()->id,
                'product_name' => $product['product_name'],
                'description' => $product['description'],
                'short_description' => $product['short_description'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'status' => 'Available',
                'is_published' => true,
            ]);
        }
    }
}
