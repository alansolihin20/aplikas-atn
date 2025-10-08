<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaryawanTable extends Migration
{
    public function up(): void
    {
        Schema::create('karyawan', function (Blueprint $table) {
            $table->increments('id'); // int(11) AUTO_INCREMENT
            $table->unsignedInteger('user_id'); // Sesuaikan dengan users.id
            $table->string('name');
            $table->string('nik', 20)->unique();
            $table->string('jabatan', 100);
            $table->date('tanggal_masuk');
            $table->decimal('gaji_pokok', 12, 2)->default(0);
            $table->decimal('tunjangan', 12, 2)->default(0);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');

            $table->timestamps();

            // Foreign key ke tabel users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
}

