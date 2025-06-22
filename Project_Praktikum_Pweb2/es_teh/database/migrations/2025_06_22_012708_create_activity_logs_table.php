<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log_name')->default('default'); // Nama grup log (misal: pesanan, user, produk)
            $table->text('description'); // Deskripsi aktivitas, e.g., "User John Doe membuat pesanan #123"
            $table->nullableMorphs('subject'); // Model yang terkait dengan aktivitas (Order, User, Product)
            $table->nullableMorphs('causer');  // User yang menyebabkan aktivitas (jika ada dan login)
            $table->json('properties')->nullable(); // Properti tambahan dalam format JSON
            $table->timestamps(); // Kapan aktivitas terjadi
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
