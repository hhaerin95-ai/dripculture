<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // clear old data
        DB::table('products')->delete();
        DB::table('categories')->delete();

        // categories
        DB::table('categories')->insert([
            [
                'category_name' => 'T-Shirts',
                'description' => 'Streetwear tees',
                'created_at' => now(),
            ],
            [
                'category_name' => 'Hoodies',
                'description' => 'Oversized hoodies',
                'created_at' => now(),
            ]
        ]);

        // products
        DB::table('products')->insert([
            [
                'category_id' => 1,
                'product_name' => 'OG Tee',
                'description' => 'Streetwear tee',
                'base_price' => 89.00,
                'status' => 'Active',
                'created_at' => now(),
            ],
            [
                'category_id' => 2,
                'product_name' => 'Black Hoodie',
                'description' => 'Oversized hoodie',
                'base_price' => 159.00,
                'status' => 'Active',
                'created_at' => now(),
            ]
        ]);
    }
}