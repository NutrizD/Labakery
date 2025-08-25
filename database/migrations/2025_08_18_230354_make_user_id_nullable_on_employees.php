<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Abaikan jika kolom tidak ada; jika ada, jadikan nullable
            if (Schema::hasColumn('employees', 'user_id')) {
                $table->foreignId('user_id')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'user_id')) {
                $table->foreignId('user_id')->nullable(false)->change();
            }
        });
    }
};
