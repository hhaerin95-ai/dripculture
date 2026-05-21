<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // DISABLE FK CHECKS (PostgreSQL)
        DB::statement('SET session_replication_role = replica;');

        // CLEAR OLD DATA
        DB::table('order_items')->delete();
        DB::table('orders')->delete();
        DB::table('cart')->delete();
        DB::table('variants')->delete();
        DB::table('images')->delete();
        DB::table('products')->delete();
        DB::table('categories')->delete();
        DB::table('users')->delete();
        DB::table('roles')->delete();

        // RE-ENABLE FK CHECKS
        DB::statement('SET session_replication_role = DEFAULT;');

        // ROLES
        DB::table('roles')->insert([
            ['role_id' => 1, 'role_name' => 'Admin'],
            ['role_id' => 2, 'role_name' => 'Customer'],
        ]);

        // CATEGORIES
        $tshirtId = DB::table('categories')->insertGetId([
            'category_name' => 'T-Shirts',
            'description'   => 'Graphic and plain tees',
            'created_at'    => now(),
        ], 'category_id');

        $hoodieId = DB::table('categories')->insertGetId([
            'category_name' => 'Hoodies',
            'description'   => 'Oversized and zip-up hoodies',
            'created_at'    => now(),
        ], 'category_id');

        $capId = DB::table('categories')->insertGetId([
            'category_name' => 'Caps',
            'description'   => 'Snapbacks and dad caps',
            'created_at'    => now(),
        ], 'category_id');

        $pantsId = DB::table('categories')->insertGetId([
            'category_name' => 'Pants',
            'description'   => 'Track pants and denim',
            'created_at'    => now(),
        ], 'category_id');

        $accId = DB::table('categories')->insertGetId([
            'category_name' => 'Accessories',
            'description'   => 'Bags, beanies and more',
            'created_at'    => now(),
        ], 'category_id');

        // PRODUCTS
        $p1 = DB::table('products')->insertGetId([
            'category_id'  => $tshirtId,
            'product_name' => 'OG Box Logo Tee',
            'description'  => 'Classic boxy fit with embroidered chest logo.',
            'base_price'   => 89.00,
            'status'       => 'Active',
            'created_at'   => now(),
        ], 'product_id');

        $p2 = DB::table('products')->insertGetId([
            'category_id'  => $tshirtId,
            'product_name' => 'Acid Wash Graphic Tee',
            'description'  => 'Washed distressed look with bold urban print.',
            'base_price'   => 75.00,
            'status'       => 'Active',
            'created_at'   => now(),
        ], 'product_id');

        $p3 = DB::table('products')->insertGetId([
            'category_id'  => $hoodieId,
            'product_name' => 'Essential Oversized Hoodie',
            'description'  => 'Drop-shoulder hoodie in fleece cotton blend.',
            'base_price'   => 149.00,
            'status'       => 'Active',
            'created_at'   => now(),
        ], 'product_id');

        $p4 = DB::table('products')->insertGetId([
            'category_id'  => $hoodieId,
            'product_name' => 'Zip-Up Tech Fleece',
            'description'  => 'Lightweight zip-up with kangaroo pocket.',
            'base_price'   => 169.00,
            'status'       => 'Active',
            'created_at'   => now(),
        ], 'product_id');

        $p5 = DB::table('products')->insertGetId([
            'category_id'  => $capId,
            'product_name' => 'Snap-Back 6 Panel Cap',
            'description'  => 'Structured 6-panel with flat brim.',
            'base_price'   => 59.00,
            'status'       => 'Active',
            'created_at'   => now(),
        ], 'product_id');

        // VARIANTS
        $variants = [
            [$p1, 'S',        'Black', 0],
            [$p1, 'M',        'Black', 0],
            [$p1, 'L',        'Black', 0],
            [$p2, 'S',        'Grey',  0],
            [$p2, 'M',        'Grey',  0],
            [$p2, 'L',        'Grey',  0],
            [$p3, 'S',        'Black', 0],
            [$p3, 'M',        'Black', 0],
            [$p3, 'L',        'Black', 0],
            [$p4, 'S',        'Black', 0],
            [$p4, 'M',        'Black', 0],
            [$p4, 'L',        'Navy',  5],
            [$p5, 'One Size', 'Black', 0],
            [$p5, 'One Size', 'White', 0],
        ];

        foreach ($variants as $i => [$productId, $size, $colour, $extra]) {
            DB::table('variants')->insert([
                'product_id'       => $productId,
                'size'             => $size,
                'colour'           => $colour,
                'sku_code'         => 'SKU-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'stock_qty'        => rand(10, 50),
                'additional_price' => $extra,
            ]);
        }

        // ADMIN USER
        DB::table('users')->insert([
            'role_id'    => 1,
            'name'       => 'Admin',
            'full_name'  => 'Admin User',
            'email'      => 'admin@dripculture.my',
            'password'   => Hash::make('Admin@1234'),
            'phone'      => '0123456789',
            'status'     => 'Active',
            'created_at' => now(),
        ]);

        // CUSTOMER USER
        DB::table('users')->insert([
            'role_id'    => 2,
            'name'       => 'Test Customer',
            'full_name'  => 'Test Customer',
            'email'      => 'customer@dripculture.my',
            'password'   => Hash::make('Customer@1234'),
            'phone'      => '0198765432',
            'status'     => 'Active',
            'created_at' => now(),
        ]);
    }
}