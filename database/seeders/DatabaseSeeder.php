<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('products')->truncate();
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Pass 'category_id' as the second argument to insertGetId
        $tshirtId = DB::table('categories')->insertGetId([
            'category_name' => 'T-Shirts',
            'description'   => 'Streetwear tees',
            'created_at'    => now(),
        ], 'category_id');  // <-- tell Laravel the PK name

        $hoodieId = DB::table('categories')->insertGetId([
            'category_name' => 'Hoodies',
            'description'   => 'Oversized hoodies',
            'created_at'    => now(),
        ], 'category_id');  // <-- same here

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