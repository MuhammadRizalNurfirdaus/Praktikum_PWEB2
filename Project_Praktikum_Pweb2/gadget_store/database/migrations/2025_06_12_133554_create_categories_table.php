<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable(); // Untuk hirarki (sub-kategori)
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable(); // Gambar ikon kategori
            $table->timestamps();

            // Foreign key constraint untuk parent_id ke tabel categories sendiri
            $table->foreign('parent_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade'); // Jika parent dihapus, children juga (atau set null)

            $table->index('name'); // Indeks untuk pencarian lebih cepat
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
