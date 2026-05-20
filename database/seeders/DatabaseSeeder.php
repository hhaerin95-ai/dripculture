<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'category_name' => 'T-Shirts',
                'description' => 'Streetwear',
                'created_at' => now(),
            ]
        ]);

        DB::table('products')->insert([
            [
                'category_id' => 1,
                'product_name' => 'OG Tee',
                'description' => 'Black streetwear tee',
                'base_price' => 89,
                'status' => 'Active',
                'created_at' => now(),
            ]
        ]);
    }
}