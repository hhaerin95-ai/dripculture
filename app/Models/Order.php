<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = 'order_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'address_id', 'order_date', 'total_amount', 'order_status',
    ];

    protected $casts = [
        'order_date' => 'datetime',
    ];

    // Alias order_date as created_at for views expecting created_at
    public function getCreatedAtAttribute()
    {
        return $this->order_date;
    }

    // Convenience accessors delegating to related address
    public function getDeliveryNameAttribute(): string
    {
        return $this->address->recipient_name ?? '';
    }

    public function getDeliveryPhoneAttribute(): string
    {
        return $this->address->phone_number ?? '';
    }

    public function getDeliveryAddressAttribute(): string
    {
        return $this->address->address_line ?? '';
    }

    public function getDeliveryPostcodeAttribute(): string
    {
        return $this->address->postcode ?? '';
    }

    public function getDeliveryStateAttribute(): string
    {
        return $this->address->state ?? '';
    }

    // Convenience accessor delegating to related payment
    public function getPaymentMethodAttribute(): string
    {
        return $this->payment->payment_method ?? '';
    }

    // Alias order_status as status
    public function getStatusAttribute(): string
    {
        return $this->order_status;
    }

    // CSS badge class based on order status
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->order_status) {
            'Pending'    => 'badge-warning',
            'Processing' => 'badge-info',
            'Packed'     => 'badge-info',
            'Shipped'    => 'badge-primary',
            'Delivered'  => 'badge-success',
            'Cancelled'  => 'badge-danger',
            default      => 'badge-secondary',
        };
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id', 'address_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'order_id');
    }

    public function histories()
    {
        return $this->hasMany(OrderHistory::class, 'order_id', 'order_id');
    }

    public function getFormattedIdAttribute(): string
    {
        return '#' . str_pad($this->order_id, 6, '0', STR_PAD_LEFT);
    }
}
