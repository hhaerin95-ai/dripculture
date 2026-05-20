<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    protected $table = 'history';
    protected $primaryKey = 'history_id';
    public $timestamps = false;
    protected $fillable = ['order_id', 'user_id', 'status', 'note', 'updated_at'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
