<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shipping_address_id',
        'billing_address_id',
        'order_number',
        'total_amount',
        'shipping_cost',
        'discount_amount',
        'grand_total',
        'status',
        'payment_method',
        'payment_status',
        'payment_token',
        'payment_url',
        'shipping_method',
        'tracking_number',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    // Relasi: Order dimiliki oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Order memiliki satu Shipping Address (Alamat Pengiriman)
    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    // Relasi: Order memiliki satu Billing Address (Alamat Penagihan)
    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    // Relasi: Order memiliki banyak OrderItem
    public function items() // atau orderItems() agar lebih jelas
    {
        return $this->hasMany(OrderItem::class);
    }
}
