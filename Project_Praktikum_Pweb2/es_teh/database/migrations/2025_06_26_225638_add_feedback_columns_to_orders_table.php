<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Rename 'rating' menjadi 'product_rating'
            $table->renameColumn('rating', 'product_rating');
            // Tambahkan kolom untuk rating kurir
            $table->unsignedTinyInteger('kurir_rating')->nullable()->after('product_rating');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('kurir_rating');
            $table->renameColumn('product_rating', 'rating');
        });
    }
};
