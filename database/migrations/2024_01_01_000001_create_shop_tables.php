<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Roles
        Schema::create('roles', function (Blueprint $table) {
            $table->id('role_id');
            $table->string('role_name', 50);
        });

        // Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id('category_id');
            $table->string('category_name', 100);
            $table->text('description')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        // Products
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');
            $table->foreignId('category_id')->constrained('categories', 'category_id')->cascadeOnDelete();
            $table->string('product_name', 150);
            $table->text('description')->nullable();
            $table->decimal('base_price', 10, 2);
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamp('created_at')->nullable();
        });

        // Variants
        Schema::create('variants', function (Blueprint $table) {
            $table->id('variant_id');
            $table->foreignId('product_id')->constrained('products', 'product_id')->cascadeOnDelete();
            $table->string('size', 20);
            $table->string('colour', 50);
            $table->string('sku_code', 50)->unique();
            $table->integer('stock_qty')->default(0);
            $table->decimal('additional_price', 10, 2)->default(0);
        });

        // Images
        Schema::create('images', function (Blueprint $table) {
            $table->id('image_id');
            $table->foreignId('product_id')->constrained('products', 'product_id')->cascadeOnDelete();
            $table->string('image_url', 255);
            $table->tinyInteger('is_primary')->default(0);
        });

        // Addresses
        Schema::create('addresses', function (Blueprint $table) {
            $table->id('address_id');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('recipient_name', 100);
            $table->string('phone_number', 20);
            $table->text('address_line');
            $table->string('postcode', 10);
            $table->string('state', 50);
            $table->tinyInteger('is_default')->default(0);
        });

        // Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('address_id')->nullable()->constrained('addresses', 'address_id')->nullOnDelete();
            $table->timestamp('order_date')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->enum('order_status', ['Pending', 'Processing', 'Packed', 'Shipped', 'Delivered', 'Cancelled'])->default('Pending');
        });

        // Payments
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');
            $table->foreignId('order_id')->constrained('orders', 'order_id')->cascadeOnDelete();
            $table->string('payment_method', 50);
            $table->enum('payment_status', ['Pending', 'Successful', 'Failed'])->default('Pending');
            $table->decimal('amount', 10, 2);
            $table->string('transaction_reference', 100)->nullable();
            $table->timestamp('payment_date')->nullable();
        });

        // Order Items
        Schema::create('order_items', function (Blueprint $table) {
            $table->id('item_id');
            $table->foreignId('order_id')->constrained('orders', 'order_id')->cascadeOnDelete();
            $table->foreignId('variant_id')->constrained('variants', 'variant_id')->cascadeOnDelete();
            $table->integer('quantity');
            $table->decimal('price_at_purchase', 10, 2);
            $table->decimal('subtotal', 10, 2);
        });

        // Order History
        Schema::create('history', function (Blueprint $table) {
            $table->id('history_id');
            $table->foreignId('order_id')->constrained('orders', 'order_id')->cascadeOnDelete();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('status', 50);
            $table->text('note')->nullable();
            $table->timestamp('updated_at')->nullable();
        });

        // Stock Log
        Schema::create('log', function (Blueprint $table) {
            $table->id('log_id');
            $table->foreignId('variant_id')->constrained('variants', 'variant_id')->cascadeOnDelete();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('change_type', ['Restock', 'Adjustment', 'Correction']);
            $table->integer('quantity_changed');
            $table->timestamp('log_date')->nullable();
        });

        // Cart (uses variant_id)
        Schema::create('cart', function (Blueprint $table) {
            $table->id('cart_id');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('variant_id')->constrained('variants', 'variant_id')->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->timestamp('added_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart');
        Schema::dropIfExists('log');
        Schema::dropIfExists('history');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('images');
        Schema::dropIfExists('variants');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('roles');
    }
};
