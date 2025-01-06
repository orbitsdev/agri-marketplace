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
       
        $farmer1 = Farmer::first();
        $farmer2 = Farmer::orderBy('id', 'desc')->first();

        if (!$farmer1 || !$farmer2) {
            $this->command->error('No farmers found. Please seed farmers first.');
            return;
        }

        // Products for the first farmer
        $productsForFarmer1 = [
            ['product_name' => 'Organic Tomatoes', 'description' => 'Freshly harvested organic tomatoes', 'quantity' => 100, 'price' => 2.50],
            ['product_name' => 'Red Apples', 'description' => 'Juicy and sweet red apples', 'quantity' => 50, 'price' => 3.00],
            ['product_name' => 'Golden Potatoes', 'description' => 'Golden potatoes perfect for cooking', 'quantity' => 200, 'price' => 1.20],
            ['product_name' => 'Sweet Corn', 'description' => 'Fresh and tender sweet corn', 'quantity' => 150, 'price' => 1.50],
            ['product_name' => 'Carrots', 'description' => 'Crunchy and vibrant carrots', 'quantity' => 120, 'price' => 2.00],
        ];

        // Products for the second farmer
        $productsForFarmer2 = [
            ['product_name' => 'Fresh Milk', 'description' => 'Creamy and fresh milk', 'quantity' => 60, 'price' => 1.50],
            ['product_name' => 'Free-range Eggs', 'description' => 'Eggs from free-range chickens', 'quantity' => 100, 'price' => 2.00],
            ['product_name' => 'Honey', 'description' => 'Pure organic honey', 'quantity' => 40, 'price' => 6.00],
            ['product_name' => 'Almonds', 'description' => 'Crunchy and nutritious almonds', 'quantity' => 80, 'price' => 4.50],
            ['product_name' => 'Cheese', 'description' => 'Aged and flavorful cheese', 'quantity' => 30, 'price' => 5.00],
        ];

        // Seed products for the first farmer
        foreach ($productsForFarmer1 as $product) {
            Product::create([
                'farmer_id' => $farmer1->id,
                'product_name' => $product['product_name'],
                'description' => $product['description'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'status' => 'Available',
                'is_published' => true,
            ]);
        }

        // Seed products for the second farmer
        foreach ($productsForFarmer2 as $product) {
            Product::create([
                'farmer_id' => $farmer2->id,
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
