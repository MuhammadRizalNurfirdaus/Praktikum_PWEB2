<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'image_path',
    ];

    // Relasi: Kategori induk (jika ini sub-kategori)
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Relasi: Sub-kategori dari kategori ini
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Relasi: Satu Category memiliki banyak Product
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
