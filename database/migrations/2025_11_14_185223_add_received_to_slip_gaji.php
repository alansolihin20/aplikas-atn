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
        Schema::table('slip_gajis', function (Blueprint $table) {
        $table->boolean('is_received')->default(false);
        $table->timestamp('received_at')->nullable();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('slip_gajis', function (Blueprint $table) {
            //
        });
    }
};
