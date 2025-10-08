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
        Schema::create('items', function (Blueprint $table) {
        $table->id();
        $table->string('kode_barang')->unique();
        $table->string('nama_barang');
        $table->string('kategori')->nullable();
        $table->string('satuan', 20);
        $table->decimal('harga_beli', 15, 2);
        $table->decimal('harga_jual', 15, 2);
        $table->integer('stok')->default(0);
        $table->string('lokasi')->nullable();
        $table->timestamp('created_at')->useCurrent();
        $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
