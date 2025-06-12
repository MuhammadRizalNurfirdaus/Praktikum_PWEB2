<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Jika Anda menggunakan softDeletes di migrasi

class Product extends Model
{
    use HasFactory, SoftDeletes; // Tambahkan SoftDeletes jika ada di migrasi

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'sku',
        'short_description',
        'long_description',
        'price',
        'sale_price',
        'stock_quantity',
        'is_featured',
        'is_active',
        'condition',
        'weight',
        'dimensions',
        'release_date',
        'warranty_info',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'release_date' => 'date',
        'weight' => 'decimal:2', // Sesuaikan jika tipe datanya bukan decimal
    ];

    // Relasi: Product dimiliki oleh satu Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi: Product dimiliki oleh satu Brand
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // Relasi: Product memiliki banyak ProductImage
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // Relasi: Product memiliki banyak ProductSpecification
    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class);
    }

    // Relasi: Product bisa ada di banyak OrderItem
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi: Product memiliki banyak Review
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Accessor untuk mendapatkan gambar utama (opsional tapi berguna)
    public function getPrimaryImageAttribute()
    {
        // Ambil gambar yang ditandai sebagai primary, atau gambar pertama jika tidak ada yang primary
        $primaryImage = $this->images()->where('is_primary', true)->first();
        if ($primaryImage) {
            return $primaryImage->image_path;
        }
        // Jika tidak ada primary, ambil gambar pertama
        $firstImage = $this->images()->first();
        return $firstImage ? $firstImage->image_path : 'path/to/default_product_image.png'; // Ganti dengan path default Anda
    }
}
