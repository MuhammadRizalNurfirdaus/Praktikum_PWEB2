<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Ganti decimal(10,2) dengan presisi yang baru jika diperlukan
            // Contoh: untuk harga hingga milyaran tanpa sen
            $table->decimal('price', 15, 0)->change();
            // Atau jika tetap dengan 2 desimal tapi angka lebih besar
            // $table->decimal('price', 12, 2)->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Kembalikan ke definisi lama jika rollback
            $table->decimal('price', 10, 2)->change();
        });
    }
};
