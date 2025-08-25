<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // jadikan user_id opsional agar tidak wajib memilih user
            if (Schema::hasColumn('employees', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->change();
            }
            // kolom baru untuk menyimpan nama yang diinput manual
            $table->string('full_name')->nullable()->after('user_id');
        });

        // Backfill: isi full_name dari nama user jika relasi masih ada
        DB::statement("
            UPDATE employees e
            JOIN users u ON u.id = e.user_id
            SET e.full_name = COALESCE(e.full_name, u.name)
            WHERE e.user_id IS NOT NULL
        ");
    }

    public function down(): void
    {
        // Kembalikan seperti semula (hati-hati jika sudah ada data full_name terpakai)
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'full_name')) {
                $table->dropColumn('full_name');
            }
            if (Schema::hasColumn('employees', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable(false)->change();
            }
        });
    }
};
