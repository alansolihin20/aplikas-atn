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
       Schema::create('stok_opname', function (Blueprint $table) {
        $table->id();
        $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
        $table->integer('stok_sistem');
        $table->integer('stok_fisik');
        $table->integer('selisih');
        $table->timestamp('tanggal')->useCurrent();
        $table->text('keterangan')->nullable();
        $table->timestamp('created_at')->useCurrent();
        $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_opname');
    }
};
