<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // clear tables
        DB::table('variants')->delete();
        DB::table('products')->delete();
        DB::table('categories')->delete();
        DB::table('users')->delete();
        DB::table('roles')->delete();

        // roles
        DB::table('roles')->insert([
            [
                'role_name' => 'Admin',
            ],
            [
                'role_name' => 'Customer',
            ],
        ]);

        // categories
        DB::table('categories')->insert([
            [
                'category_name' => 'T-Shirts',
                'description' => 'Graphic tees',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Hoodies',
                'description' => 'Street hoodies',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // products
        DB::table('products')->insert([
            [
                'category_id' => 1,
                'product_name' => 'OG Box Tee',
                'description' => 'Streetwear tee',
                'base_price' => 89,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'product_name' => 'Oversized Hoodie',
                'description' => 'Black hoodie',
                'base_price' => 149,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // variants
        DB::table('variants')->insert([
            [
                'product_id' => 1,
                'size' => 'M',
                'colour' => 'Black',
                'sku_code' => 'SKU001',
                'stock_qty' => 10,
                'additional_price' => 0,
            ],
            [
                'product_id' => 2,
                'size' => 'L',
                'colour' => 'Black',
                'sku_code' => 'SKU002',
                'stock_qty' => 5,
                'additional_price' => 0,
            ],
        ]);

        // users
        DB::table('users')->insert([
            [
                'role_id' => 1,
                'name' => 'Admin',
                'full_name' => 'Admin User',
                'email' => 'admin@dripculture.my',
                'password' => Hash::make('Admin123'),
                'phone' => '0123456789',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}