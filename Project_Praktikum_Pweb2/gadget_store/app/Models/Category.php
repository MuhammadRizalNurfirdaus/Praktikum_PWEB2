<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug'; // Memberitahu Laravel untuk menggunakan kolom 'slug'
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
            // Pastikan slug unik saat membuat
            $originalSlug = $category->slug;
            $count = 1;
            while (static::whereSlug($category->slug)->exists()) {
                $category->slug = "{$originalSlug}-{$count}";
                $count++;
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') || empty($category->slug)) {
                $slug = Str::slug($category->name);
                if ($category->slug !== $slug) { // Hanya update jika slug berubah
                    $category->slug = $slug;
                    // Pastikan slug unik saat mengupdate, kecuali untuk model itu sendiri
                    $originalSlug = $category->slug;
                    $count = 1;
                    while (static::whereSlug($category->slug)->where('id', '!=', $category->id)->exists()) {
                        $category->slug = "{$originalSlug}-{$count}";
                        $count++;
                    }
                }
            }
        });
    }
}
