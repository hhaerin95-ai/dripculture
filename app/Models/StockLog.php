<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    protected $table = 'log';
    protected $primaryKey = 'log_id';
    public $timestamps = false;
    protected $fillable = ['variant_id', 'user_id', 'change_type', 'quantity_changed', 'log_date'];

    public function variant()
    {
        return $this->belongsTo(Variant::class, 'variant_id', 'variant_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
