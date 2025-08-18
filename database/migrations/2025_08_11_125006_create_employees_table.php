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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('employee_id')->unique(); // Nomor ID karyawan
            $table->string('position'); // Jabatan/posisi
            $table->string('phone')->nullable(); // Nomor telepon
            $table->text('address')->nullable(); // Alamat
            $table->date('hire_date'); // Tanggal bergabung
            $table->decimal('salary', 12, 2)->nullable(); // Gaji
            $table->enum('status', ['active', 'inactive', 'terminated'])->default('active'); // Status karyawan
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
