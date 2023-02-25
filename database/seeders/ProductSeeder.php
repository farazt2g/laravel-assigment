<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

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
            [
                'name' => 'Product 1',
                'description' => 'Product 1 description',
                'price' => 10.99
            ],
            [
                'name' => 'Product 2',
                'description' => 'Product 2 description',
                'price' => 20.99
            ],
            [
                'name' => 'Product 3',
                'description' => 'Product 3 description',
                'price' => 5.99
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
