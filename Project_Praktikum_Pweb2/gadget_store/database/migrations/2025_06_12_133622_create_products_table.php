<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // Foreign key ke tabel categories
            $table->foreignId('brand_id')->constrained('brands')->onDelete('cascade');     // Foreign key ke tabel brands
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique()->nullable(); // Stock Keeping Unit
            $table->text('short_description')->nullable();
            $table->longText('long_description')->nullable();
            $table->decimal('price', 12, 2); // Harga normal
            $table->decimal('sale_price', 12, 2)->nullable(); // Harga diskon
            $table->integer('stock_quantity')->default(0);
            $table->boolean('is_featured')->default(false); // Produk unggulan
            $table->boolean('is_active')->default(true); // Status aktif produk
            $table->enum('condition', ['baru', 'bekas', 'refurbished'])->default('baru');
            $table->decimal('weight', 8, 2)->nullable(); // Berat produk (misal dalam kg)
            $table->string('dimensions')->nullable(); // Dimensi (misal: PxLxT cm)
            $table->date('release_date')->nullable();
            $table->string('warranty_info')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Untuk fitur soft delete (opsional)

            $table->index('name');
            $table->index('price');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
