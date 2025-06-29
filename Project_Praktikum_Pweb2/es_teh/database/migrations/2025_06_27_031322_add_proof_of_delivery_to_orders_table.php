<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('orders', 'proof_of_delivery')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('proof_of_delivery')->nullable()->after('notes'); // 'notes' pasti ada
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'proof_of_delivery')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('proof_of_delivery');
            });
        }
    }
};
