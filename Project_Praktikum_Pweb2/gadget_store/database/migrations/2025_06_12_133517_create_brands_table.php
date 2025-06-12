<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id(); // Primary key auto-increment
            $table->string('name')->unique(); // Nama brand, harus unik
            $table->string('slug')->unique(); // Untuk URL friendly, harus unik
            $table->string('logo_path')->nullable(); // Path ke file logo brand
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
