<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')
                ->nullable() // Izinkan produk tidak memiliki kategori (opsional)
                ->after('id') // Penempatan kolom (opsional)
                ->constrained('categories') // Foreign key ke tabel categories
                ->onDelete('set null'); // Jika kategori dihapus, set category_id di produk jadi NULL
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
