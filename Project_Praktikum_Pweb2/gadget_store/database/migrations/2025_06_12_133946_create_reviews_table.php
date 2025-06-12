<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->unsignedTinyInteger('rating'); // 1-5 bintang
            $table->text('comment')->nullable();
            $table->boolean('is_approved')->default(true); // Untuk moderasi review
            $table->timestamps();

            $table->unique(['user_id', 'product_id']); // Satu user hanya boleh review satu produk sekali
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
