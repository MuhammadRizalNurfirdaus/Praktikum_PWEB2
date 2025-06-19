<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_orders_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // ID Pesanan (Primary Key, Auto Increment)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID User yang memesan (Foreign Key ke tabel users)
            $table->string('order_number')->unique(); // Nomor pesanan unik (bisa digenerate)
            $table->decimal('total_amount', 15, 2); // Total harga pesanan
            $table->string('status')->default('pending'); // Status pesanan: pending, processing, shipped, delivered, cancelled, etc.
            $table->string('customer_name'); // Nama pelanggan (bisa diambil dari user atau diinput)
            $table->string('customer_email');
            $table->text('shipping_address'); // Alamat pengiriman
            $table->string('customer_phone')->nullable();
            $table->string('payment_method')->nullable(); // Metode pembayaran
            $table->string('payment_status')->default('unpaid'); // Status pembayaran: unpaid, paid, failed
            $table->text('notes')->nullable(); // Catatan tambahan dari pelanggan
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
