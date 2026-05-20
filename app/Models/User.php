<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'role_id', 'full_name', 'name', 'email', 'password',
        'phone', 'phone_number', 'address', 'postcode', 'state', 'status',
    ];

    protected $hidden = ['password', 'remember_token'];

    // Accessor: admin code uses full_name, web uses name
    public function getFullNameAttribute(): string
    {
        return $this->attributes['full_name'] ?? $this->attributes['name'] ?? '';
    }

    // Accessor: admin code uses phone_number, web uses phone
    public function getPhoneNumberAttribute(): string
    {
        return $this->attributes['phone_number'] ?? $this->attributes['phone'] ?? '';
    }

    public function cart()
    {
        return $this->hasMany(Cart::class, 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class, 'user_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}