<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    protected $primaryKey = 'variant_id';

    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'size',
        'colour',
        'sku_code',
        'stock_qty',
        'additional_price'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}