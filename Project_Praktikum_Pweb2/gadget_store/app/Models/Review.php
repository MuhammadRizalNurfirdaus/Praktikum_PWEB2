<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'comment',
        'is_approved',
    ];

    protected $casts = [
        'rating' => 'integer', // Pastikan ini integer di database (misal: tinyInteger)
        'is_approved' => 'boolean',
    ];

    // Relasi: Review dimiliki oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Review dimiliki oleh satu Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
