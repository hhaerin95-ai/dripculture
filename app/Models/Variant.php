<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    protected $primaryKey = 'variant_id';
    public $timestamps = false;
    protected $fillable = ['product_id', 'size', 'colour', 'sku_code', 'stock_qty', 'additional_price'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'variant_id', 'variant_id');
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class, 'variant_id', 'variant_id');
    }

    public function stockLogs()
    {
        return $this->hasMany(StockLog::class, 'variant_id', 'variant_id');
    }

    public function getPriceAttribute(): float
    {
        return $this->product->base_price + $this->additional_price;
    }
}
