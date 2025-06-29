<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hanya rename jika kolom 'rating' ada
        if (Schema::hasColumn('orders', 'rating')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->renameColumn('rating', 'product_rating');
            });
        }

        // Tambahkan kolom kurir_rating setelah kolom 'status'
        if (!Schema::hasColumn('orders', 'kurir_rating')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->unsignedTinyInteger('kurir_rating')->nullable()->after('status');
            });
        }
    }

    public function down(): void
    {
        // Drop kurir_rating kalau ada
        if (Schema::hasColumn('orders', 'kurir_rating')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('kurir_rating');
            });
        }

        // Rename kembali jika kolom 'product_rating' ada
        if (Schema::hasColumn('orders', 'product_rating')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->renameColumn('product_rating', 'rating');
            });
        }
    }
};
