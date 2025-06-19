<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama produk, e.g., "Es Teh Poci Aji Manis"
            $table->string('size'); // Ukuran, e.g., "Besar", "Sedang"
            $table->decimal('price', 8, 2); // Harga, misal 5000.00
            $table->string('image_path'); // Path ke gambar produk
            $table->text('description')->nullable(); // Deskripsi singkat (opsional)
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
