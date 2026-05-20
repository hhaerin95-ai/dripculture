<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        DB::table('roles')->insert([
            ['role_id' => 1, 'role_name' => 'Admin'],
            ['role_id' => 2, 'role_name' => 'Customer'],
        ]);

        // Categories
        DB::table('categories')->insert([
            ['category_name' => 'T-Shirts',     'description' => 'Graphic and plain tees', 'created_at' => now()],
            ['category_name' => 'Hoodies',       'description' => 'Oversized and zip-up hoodies', 'created_at' => now()],
            ['category_name' => 'Caps',          'description' => 'Snapbacks and dad caps', 'created_at' => now()],
            ['category_name' => 'Pants',         'description' => 'Track pants and denim', 'created_at' => now()],
            ['category_name' => 'Accessories',   'description' => 'Bags, beanies and more', 'created_at' => now()],
        ]);

        // Products (using new schema: product_name, base_price, status)
        $products = [
            ['category_id' => 1, 'product_name' => 'OG Box Logo Tee',       'description' => 'Classic boxy fit with embroidered chest logo.', 'base_price' => 89.00,  'status' => 'Active', 'created_at' => now()],
            ['category_id' => 1, 'product_name' => 'Acid Wash Graphic Tee', 'description' => 'Washed distressed look with bold urban print.',  'base_price' => 75.00,  'status' => 'Active', 'created_at' => now()],
            ['category_id' => 2, 'product_name' => 'Essential Oversized Hoodie', 'description' => 'Drop-shoulder hoodie in fleece cotton blend.', 'base_price' => 149.00, 'status' => 'Active', 'created_at' => now()],
            ['category_id' => 2, 'product_name' => 'Zip-Up Tech Fleece',    'description' => 'Lightweight zip-up with kangaroo pocket.',       'base_price' => 169.00, 'status' => 'Active', 'created_at' => now()],
            ['category_id' => 3, 'product_name' => 'Snap-Back 6 Panel Cap', 'description' => 'Structured 6-panel with flat brim.',             'base_price' => 59.00,  'status' => 'Active', 'created_at' => now()],
            ['category_id' => 3, 'product_name' => 'Vintage Dad Cap',       'description' => 'Unstructured low-profile cap.',                  'base_price' => 49.00,  'status' => 'Active', 'created_at' => now()],
            ['category_id' => 4, 'product_name' => 'Cargo Track Pants',     'description' => 'Multi-pocket cargo pants with tapered leg.',     'base_price' => 129.00, 'status' => 'Active', 'created_at' => now()],
            ['category_id' => 4, 'product_name' => 'Wide Leg Denim',        'description' => 'Relaxed wide-leg cut in 14oz raw denim.',        'base_price' => 199.00, 'status' => 'Active', 'created_at' => now()],
            ['category_id' => 5, 'product_name' => 'Shoulder Bag',          'description' => 'Compact crossbody bag with contrast logo patch.','base_price' => 99.00,  'status' => 'Active', 'created_at' => now()],
            ['category_id' => 5, 'product_name' => 'Beanie Knit Hat',       'description' => 'Ribbed knit beanie with fold-over cuff.',        'base_price' => 39.00,  'status' => 'Active', 'created_at' => now()],
        ];
        DB::table('products')->insert($products);

        // Variants for each product
        $variantData = [
            1 => [['S','Black',0],['M','Black',0],['L','Black',0],['S','White',0],['M','White',0]],
            2 => [['S','Grey',0],['M','Grey',0],['L','Grey',0],['M','Navy',5]],
            3 => [['S','Black',0],['M','Black',0],['L','Black',0],['XL','Black',10],['M','Charcoal',0]],
            4 => [['S','Black',0],['M','Black',0],['L','Navy',5]],
            5 => [['One Size','Black',0],['One Size','White',0],['One Size','Sand',0]],
            6 => [['One Size','Black',0],['One Size','Beige',0],['One Size','Olive',0]],
            7 => [['S','Black',0],['M','Black',0],['L','Olive',0],['XL','Grey',0]],
            8 => [['28','Indigo',0],['30','Indigo',0],['32','Washed Black',10],['34','Washed Black',10]],
            9 => [['One Size','Black',0],['One Size','Brown',0]],
            10 => [['One Size','Black',0],['One Size','Grey',0],['One Size','Cream',0]],
        ];

        $variantId = 1;
        foreach ($variantData as $productId => $variants) {
            foreach ($variants as [$size, $colour, $extra]) {
                DB::table('variants')->insert([
                    'product_id'       => $productId,
                    'size'             => $size,
                    'colour'           => $colour,
                    'sku_code'         => 'SKU-' . str_pad($variantId, 4, '0', STR_PAD_LEFT),
                    'stock_qty'        => rand(10, 50),
                    'additional_price' => $extra,
                ]);
                $variantId++;
            }
        }

        // Admin user (role_id = 1)
        DB::table('users')->insert([
            'role_id'    => 1,
            'name'       => 'Admin',
            'full_name'  => 'Admin User',
            'email'      => 'admin@dripculture.my',
            'password'   => Hash::make('Admin@1234'),
            'phone'      => '0123456789',
            'status'     => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Sample customer user (role_id = 2)
        DB::table('users')->insert([
            'role_id'    => 2,
            'name'       => 'Test Customer',
            'full_name'  => 'Test Customer',
            'email'      => 'customer@dripculture.my',
            'password'   => Hash::make('Customer@1234'),
            'phone'      => '0198765432',
            'status'     => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
