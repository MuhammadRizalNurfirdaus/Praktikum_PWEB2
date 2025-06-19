<?php

// database/migrations/xxxx_xx_xx_xxxxxx_modify_image_path_to_nullable_in_products_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('image_path')->nullable()->change(); // Ubah menjadi nullable
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Kembalikan ke tidak nullable jika perlu, tapi hati-hati jika sudah ada data null
            $table->string('image_path')->nullable(false)->change();
        });
    }
};
