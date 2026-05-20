<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $primaryKey = 'address_id';
    public $timestamps = false;
    protected $fillable = [
        'user_id', 'recipient_name', 'phone_number', 'address_line',
        'postcode', 'state', 'is_default',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
