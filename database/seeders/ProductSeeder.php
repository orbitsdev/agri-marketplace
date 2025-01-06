<?php

namespace Database\Seeders;

use App\Models\Farmer;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $farmer = Farmer::first();

        if (!$farmer) {
            $this->command->error('No farmers found. Please seed farmers first.');
            return;
        }

        // Define meaningful agricultural products
        $products = [
            ['product_name' => 'Organic Tomatoes', 'description' => 'Freshly harvested organic tomatoes', 'quantity' => 100, 'price' => 2.50],
            ['product_name' => 'Red Apples', 'description' => 'Juicy and sweet red apples', 'quantity' => 50, 'price' => 3.00],
            ['product_name' => 'Golden Potatoes', 'description' => 'Golden potatoes perfect for cooking', 'quantity' => 200, 'price' => 1.20],
            ['product_name' => 'Sweet Corn', 'description' => 'Fresh and tender sweet corn', 'quantity' => 150, 'price' => 1.50],
            ['product_name' => 'Carrots', 'description' => 'Crunchy and vibrant carrots', 'quantity' => 120, 'price' => 2.00],
            ['product_name' => 'Broccoli', 'description' => 'Green and healthy broccoli heads', 'quantity' => 80, 'price' => 2.75],
            ['product_name' => 'Spinach', 'description' => 'Fresh leafy green spinach', 'quantity' => 60, 'price' => 1.80],
            ['product_name' => 'Strawberries', 'description' => 'Fresh and sweet strawberries', 'quantity' => 40, 'price' => 4.50],
            ['product_name' => 'Wheat', 'description' => 'High-quality wheat grains', 'quantity' => 300, 'price' => 0.80],
            ['product_name' => 'Rice', 'description' => 'Premium quality rice', 'quantity' => 500, 'price' => 1.00],
        ];

        // Seed the products for the farmer
        foreach ($products as $product) {
            Product::create([
                'farmer_id' => $farmer->id,
                'product_name' => $product['product_name'],
                'description' => $product['description'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'status' => 'Available',
                'is_published' => true,
            ]);
        }
    }
}
