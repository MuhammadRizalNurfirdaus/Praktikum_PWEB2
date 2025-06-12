<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name', // Snapshot nama produk
        'product_sku',  // Snapshot SKU produk
        'quantity',
        'price_at_purchase', // Snapshot harga satuan
        'total_price',       // Snapshot total harga item
    ];

    protected $casts = [
        'price_at_purchase' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // Relasi: OrderItem dimiliki oleh satu Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi: OrderItem merujuk ke satu Product
    // Gunakan withTrashed() jika produk bisa di soft delete dan Anda masih ingin menampilkan info produk di order lama
    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }
}
