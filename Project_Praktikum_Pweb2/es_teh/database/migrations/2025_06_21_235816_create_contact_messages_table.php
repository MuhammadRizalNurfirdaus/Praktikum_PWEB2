<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Jika user login, jika tidak null
            $table->string('name'); // Nama pengirim (bisa diisi otomatis jika user login)
            $table->string('email'); // Email pengirim (bisa diisi otomatis jika user login)
            $table->string('subject'); // Subjek pesan
            $table->text('message'); // Isi pesan
            $table->string('status')->default('Baru'); // Status: Baru, Dibaca, Dibalas, Ditutup
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
