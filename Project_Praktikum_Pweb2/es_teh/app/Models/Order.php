<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\OrderItem;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'status',
        'customer_name',
        'customer_email',
        'shipping_address',
        'customer_phone',
        'payment_method',
        'payment_status',
        'notes',
    ];

    /**
     * Relasi ke User: Satu pesanan dimiliki oleh satu user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke OrderItems: Satu pesanan memiliki banyak item.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Anda bisa tambahkan method lain di sini, misal untuk generate order number
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                // Generate nomor pesanan unik, contoh: INV-YYYYMMDD-XXXXX
                $order->order_number = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(5));
            }
        });
    }
}
