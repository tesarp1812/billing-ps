<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            ['category' => 'Food', 'name' => 'Indomie', 'price' => 12000, 'stock' => 50],
            ['category' => 'Drink', 'name' => 'Teh Botol', 'price' => 7000, 'stock' => 80],
            ['category' => 'Drink', 'name' => 'Air Mineral', 'price' => 5000, 'stock' => 100],
            ['category' => 'Drink', 'name' => 'Kopi', 'price' => 8000, 'stock' => 60],
            ['category' => 'Snack', 'name' => 'Snack', 'price' => 6000, 'stock' => 90],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['name' => $product['name']],
                array_merge($product, ['is_active' => true])
            );
        }
    }
}
