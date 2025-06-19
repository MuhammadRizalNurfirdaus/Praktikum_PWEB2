<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_order_items_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id(); // ID Item Pesanan
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // ID Pesanan (Foreign Key ke tabel orders)
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // ID Produk (Foreign Key ke tabel products)
            $table->integer('quantity'); // Jumlah produk yang dipesan
            $table->decimal('price', 15, 2); // Harga satuan produk saat dipesan (penting jika harga produk berubah)
            $table->decimal('sub_total', 15, 2); // Harga total untuk item ini (quantity * price)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
