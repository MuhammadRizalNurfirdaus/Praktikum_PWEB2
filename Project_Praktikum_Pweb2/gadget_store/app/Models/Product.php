<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image_path',
        'category_id',
    ];

    // Relasi: Satu produk milik satu kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
