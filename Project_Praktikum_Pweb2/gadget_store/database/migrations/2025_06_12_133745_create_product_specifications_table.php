<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_specifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('name'); // Nama spesifikasi (Contoh: "RAM", "Ukuran Layar")
            $table->string('value'); // Nilai spesifikasi (Contoh: "8 GB", "6.5 Inch")
            $table->string('unit')->nullable(); // Satuan, jika value hanya angka (misal: GB, Inch, MP)
            $table->timestamps();

            $table->index(['product_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_specifications');
    }
};
