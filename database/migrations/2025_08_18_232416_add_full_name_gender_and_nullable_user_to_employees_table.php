<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Tambahkan kolom full_name jika belum ada
            if (!Schema::hasColumn('employees', 'full_name')) {
                $table->string('full_name')->nullable()->after('user_id');
            }

            // Tambahkan kolom gender jika belum ada
            // (pakai string agar lintas-DB; validasi tetap membatasi male/female)
            if (!Schema::hasColumn('employees', 'gender')) {
                $table->string('gender', 10)->nullable()->after('full_name');
            }
        });

        // Jadikan user_id nullable (opsional, hanya jika Anda ingin bisa buat karyawan tanpa tautan user)
        // Perlu doctrine/dbal untuk change()
        if (Schema::hasColumn('employees', 'user_id')) {
            try {
                Schema::table('employees', function (Blueprint $table) {
                    $table->unsignedBigInteger('user_id')->nullable()->change();
                });
            } catch (\Throwable $e) {
                // Abaikan bila DBAL belum terpasang; lihat catatan di bawah
            }
        }
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'gender')) {
                $table->dropColumn('gender');
            }
            if (Schema::hasColumn('employees', 'full_name')) {
                $table->dropColumn('full_name');
            }
        });
    }
};
