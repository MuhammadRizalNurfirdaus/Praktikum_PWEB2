<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('label')->nullable(); // Misal: "Rumah", "Kantor"
            $table->string('recipient_name');
            $table->string('phone_number');
            $table->string('address_line1');
            $table->string('address_line2')->nullable();
            $table->string('city');
            $table->string('province');
            $table->string('postal_code', 10);
            $table->string('country')->default('Indonesia');
            $table->boolean('is_primary_shipping')->default(false);
            $table->boolean('is_primary_billing')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
