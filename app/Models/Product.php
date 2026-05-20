<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'product_id';

    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'product_name',
        'description',
        'base_price',
        'status'
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

    public function getRouteKeyName()
    {
        return 'product_id';
    }
}