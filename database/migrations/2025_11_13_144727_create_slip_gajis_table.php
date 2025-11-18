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
        Schema::create('slip_gajis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->date('periode'); // contoh: 2025-11-01
        $table->decimal('gaji_pokok', 12, 2);
        $table->decimal('insentif_harian', 12, 2)->default(0);
        $table->integer('hari_kerja')->default(26);
        $table->decimal('bpjs_tk', 12, 2)->nullable();
        $table->decimal('bpjs_kes', 12, 2)->nullable();
        $table->decimal('pinjaman', 12, 2)->nullable();
        $table->decimal('gaji_bruto', 12, 2)->nullable();
        $table->decimal('gaji_bersih', 12, 2)->nullable();
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slip_gajis');
    }
};
