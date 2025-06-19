<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'sub_total',
    ];

    /**
     * Relasi ke Order: Satu item pesanan dimiliki oleh satu pesanan.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relasi ke Product: Satu item pesanan merujuk ke satu produk.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
