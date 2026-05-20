<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';
    protected $primaryKey = 'cart_id';
    public $timestamps = false;

    protected $fillable = ['user_id', 'variant_id', 'quantity', 'added_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class, 'variant_id', 'variant_id');
    }

    public function getSubtotalAttribute(): float
    {
        return $this->variant->price * $this->quantity;
    }
}
