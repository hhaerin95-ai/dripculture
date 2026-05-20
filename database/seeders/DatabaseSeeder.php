<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // PostgreSQL way to disable foreign key checks
        DB::statement('SET session_replication_role = replica;');
        DB::table('products')->truncate();
        DB::table('categories')->truncate();
        DB::statement('SET session_replication_role = DEFAULT;');

        $tshirtId = DB::table('categories')->insertGetId([
            'category_name' => 'T-Shirts',
            'description'   => 'Streetwear tees',
            'created_at'    => now(),
        ], 'category_id');

        $hoodieId = DB::table('categories')->insertGetId([
            'category_name' => 'Hoodies',
            'description'   => 'Oversized hoodies',
            'created_at'    => now(),
        ], 'category_id');

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