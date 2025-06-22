<?php

namespace App\Models; // Pastikan namespace ini benar

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <--- PASTIKAN INI DIIMPORT
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str; // Untuk generate order number

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',          // User yang membuat pesanan (pelanggan)
        'kurir_id',         // <--- TAMBAHKAN INI KE $fillable jika akan diisi via mass assignment
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
     * Relasi ke User (pelanggan yang membuat pesanan).
     * Satu pesanan dimiliki oleh satu user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id'); // 'user_id' adalah foreign key di tabel orders
    }

    /**
     * Relasi ke User (kurir yang ditugaskan untuk pesanan ini).
     * Satu pesanan bisa ditugaskan ke satu kurir.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kurir(): BelongsTo // <--- TAMBAHKAN METHOD RELASI INI
    {
        // 'kurir_id' adalah nama foreign key di tabel 'orders' yang merujuk ke 'id' di tabel 'users'
        // Jika nama foreign key Anda berbeda, sesuaikan di sini.
        return $this->belongsTo(User::class, 'kurir_id');
    }

    /**
     * Relasi ke OrderItems: Satu pesanan memiliki banyak item.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Boot method untuk model.
     * Digunakan untuk otomatis generate order_number saat membuat pesanan baru.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                // Generate nomor pesanan unik, contoh: INV-YYYYMMDD-XXXXX
                // Pastikan tidak ada duplikasi jika ada potensi race condition (jarang terjadi untuk ini)
                $prefix = 'INV-';
                $datePart = date('Ymd');
                $uniquePart = strtoupper(Str::random(5));
                $orderNumber = $prefix . $datePart . '-' . $uniquePart;

                // Cek sederhana untuk keunikan, bisa dibuat lebih robust jika perlu
                while (static::where('order_number', $orderNumber)->exists()) {
                    $uniquePart = strtoupper(Str::random(5));
                    $orderNumber = $prefix . $datePart . '-' . $uniquePart;
                }
                $order->order_number = $orderNumber;
            }
        });
    }
}
