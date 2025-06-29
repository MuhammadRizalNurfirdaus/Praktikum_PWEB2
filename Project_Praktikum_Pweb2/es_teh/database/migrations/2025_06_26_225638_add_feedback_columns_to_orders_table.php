<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Rename 'rating' ke 'product_rating' jika ada
        if (Schema::hasColumn('orders', 'rating')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->renameColumn('rating', 'product_rating');
            });
        }

        // Tambahkan 'kurir_rating' jika 'product_rating' sudah ada dan 'kurir_rating' belum ada
        if (
            Schema::hasColumn('orders', 'product_rating') &&
            !Schema::hasColumn('orders', 'kurir_rating')
        ) {
            Schema::table('orders', function (Blueprint $table) {
                $table->unsignedTinyInteger('kurir_rating')->nullable()->after('product_rating');
            });
        }
    }

    public function down(): void
    {
        // Drop kolom 'kurir_rating' jika ada
        if (Schema::hasColumn('orders', 'kurir_rating')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('kurir_rating');
            });
        }

        // Rename kembali ke 'rating' jika 'product_rating' ada
        if (Schema::hasColumn('orders', 'product_rating')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->renameColumn('product_rating', 'rating');
            });
        }
    }
};
