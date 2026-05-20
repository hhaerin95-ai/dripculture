```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // RESET TABLES
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('variants')->truncate();
        DB::table('products')->truncate();
        DB::table('categories')->truncate();
        DB::table('users')->truncate();
        DB::table('roles')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ROLES
        DB::table('roles')->insert([
            [
                'role_id' => 1,
                'role_name' => 'Admin',
            ],
            [
                'role_id' => 2,
                'role_name' => 'Customer',
            ],
        ]);

        // CATEGORIES
        DB::table('categories')->insert([
            [
                'category_name' => 'T-Shirts',
                'description' => 'Graphic and plain tees',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Hoodies',
                'description' => 'Oversized and zip-up hoodies',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Caps',
                'description' => 'Snapbacks and dad caps',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Pants',
                'description' => 'Track pants and denim',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Accessories',
                'description' => 'Bags, beanies and more',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // PRODUCTS
        $products = [
            [
                'category_id' => 1,
                'product_name' => 'OG Box Logo Tee',
                'description' => 'Classic boxy fit with embroidered chest logo.',
                'base_price' => 89.00,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 1,
                'product_name' => 'Acid Wash Graphic Tee',
                'description' => 'Washed distressed look with bold urban print.',
                'base_price' => 75.00,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'product_name' => 'Essential Oversized Hoodie',
                'description' => 'Drop-shoulder hoodie in fleece cotton blend.',
                'base_price' => 149.00,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'product_name' => 'Zip-Up Tech Fleece',
                'description' => 'Lightweight zip-up with kangaroo pocket.',
                'base_price' => 169.00,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 3,
                'product_name' => 'Snap-Back 6 Panel Cap',
                'description' => 'Structured 6-panel with flat brim.',
                'base_price' => 59.00,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('products')->insert($products);

        // VARIANTS
        $variantData = [
            1 => [['S', 'Black', 0], ['M', 'Black', 0], ['L', 'Black', 0]],
            2 => [['S', 'Grey', 0], ['M', 'Grey', 0], ['L', 'Grey', 0]],
            3 => [['S', 'Black', 0], ['M', 'Black', 0], ['L', 'Black', 0]],
            4 => [['S', 'Black', 0], ['M', 'Black', 0], ['L', 'Navy', 5]],
            5 => [['One Size', 'Black', 0], ['One Size', 'White', 0]],
        ];

        $variantId = 1;

        foreach ($variantData as $productId => $variants) {
            foreach ($variants as [$size, $colour, $extra]) {

                DB::table('variants')->insert([
                    'product_id' => $productId,
                    'size' => $size,
                    'colour' => $colour,
                    'sku_code' => 'SKU-' . str_pad($variantId, 4, '0', STR_PAD_LEFT),
                    'stock_qty' => rand(10, 50),
                    'additional_price' => $extra,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $variantId++;
            }
        }

        // ADMIN USER
        DB::table('users')->insert([
            'role_id' => 1,
            'name' => 'Admin',
            'full_name' => 'Admin User',
            'email' => 'admin@dripculture.my',
            'password' => Hash::make('Admin@1234'),
            'phone' => '0123456789',
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // CUSTOMER USER
        DB::table('users')->insert([
            'role_id' => 2,
            'name' => 'Test Customer',
            'full_name' => 'Test Customer',
            'email' => 'customer@dripculture.my',
            'password' => Hash::make('Customer@1234'),
            'phone' => '0198765432',
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
```
