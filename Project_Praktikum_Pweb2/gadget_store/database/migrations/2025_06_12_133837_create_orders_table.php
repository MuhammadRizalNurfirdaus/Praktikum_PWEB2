<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('shipping_address_id')->nullable(); // Bisa merujuk ke tabel addresses
            $table->unsignedBigInteger('billing_address_id')->nullable();  // Bisa merujuk ke tabel addresses

            $table->string('order_number')->unique();
            $table->decimal('total_amount', 12, 2); // Subtotal sebelum ongkir dan diskon
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('grand_total', 12, 2); // Total yang harus dibayar
            $table->string('status')->default('pending'); // pending, processing, shipped, delivered, cancelled, refunded
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('pending'); // pending, paid, failed, expired
            $table->string('payment_token')->nullable();
            $table->string('payment_url')->nullable();
            $table->string('shipping_method')->nullable();
            $table->string('tracking_number')->nullable();
            $table->text('notes')->nullable(); // Catatan dari pelanggan
            $table->timestamps();

            // Foreign key constraints untuk alamat (opsional, tergantung bagaimana Anda ingin menyimpan alamat pesanan)
            $table->foreign('shipping_address_id')->references('id')->on('addresses')->onDelete('set null');
            $table->foreign('billing_address_id')->references('id')->on('addresses')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
