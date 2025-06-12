<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null'); // Set null jika produk dihapus dari katalog utama
            $table->string('product_name'); // Simpan nama produk saat dibeli (snapshot)
            $table->string('product_sku')->nullable(); // Simpan SKU produk saat dibeli
            $table->unsignedInteger('quantity');
            $table->decimal('price_at_purchase', 12, 2); // Harga satuan produk saat dibeli
            $table->decimal('total_price', 12, 2); // quantity * price_at_purchase
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
