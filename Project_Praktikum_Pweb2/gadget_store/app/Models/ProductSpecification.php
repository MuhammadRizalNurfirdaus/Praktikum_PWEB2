<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'value',
        'unit',
    ];

    // Relasi: ProductSpecification dimiliki oleh satu Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
