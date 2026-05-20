<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'product_id';
    public $timestamps = false;

    protected $fillable = [
        'category_id', 'product_name', 'description', 'base_price', 'status', 'created_at',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function variants()
    {
        return $this->hasMany(Variant::class, 'product_id', 'product_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'product_id', 'product_id');
    }

    public function getPrimaryImageAttribute()
    {
        return $this->images->firstWhere('is_primary', 1) ?? $this->images->first();
    }

    /** Total stock across all variants */
    public function getStockQtyAttribute(): int
    {
        return $this->variants->sum('stock_qty');
    }

    /** Stock <= 5 but still available */
    public function isLowStock(): bool
    {
        $total = $this->variants->sum('stock_qty');
        return $total > 0 && $total <= 5;
    }

    /** Completely out of stock */
    public function isOutOfStock(): bool
    {
        return $this->variants->sum('stock_qty') === 0;
    }

    /** Featured = has variants with stock > 0 and is Active */
    public function getIsFeaturedAttribute(): bool
    {
        return $this->status === 'Active' && $this->variants->where('stock_qty', '>', 0)->isNotEmpty();
    }
}