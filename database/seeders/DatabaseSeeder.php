<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // truncate resets auto-increment, delete() does not
        DB::table('products')->truncate();
        DB::table('categories')->truncate();

        // categories — capture inserted IDs
        $tshirtId = DB::table('categories')->insertGetId([
            'category_name' => 'T-Shirts',
            'description'   => 'Streetwear tees',
            'created_at'    => now(),
        ]);

        $hoodieId = DB::table('categories')->insertGetId([
            'category_name' => 'Hoodies',
            'description'   => 'Oversized hoodies',
            'created_at'    => now(),
        ]);

        // products — use real IDs, not hardcoded 1 & 2
        DB::table('products')->insert([
            [
                'category_id'  => $tshirtId,
                'product_name' => 'OG Tee',
                'description'  => 'Streetwear tee',
                'base_price'   => 89.00,
                'status'       => 'Active',
                'created_at'   => now(),
            ],
            [
                'category_id'  => $hoodieId,
                'product_name' => 'Black Hoodie',
                'description'  => 'Oversized hoodie',
                'base_price'   => 159.00,
                'status'       => 'Active',
                'created_at'   => now(),
            ],
        ]);
    }
}