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
        Schema::create('pppoe_users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // nama PPPoE user
            $table->string('password'); // password PPPoE
            $table->string('profile')->nullable(); // nama profile di Mikrotik
            $table->string('service')->nullable(); // biasanya "pppoe"
            $table->string('caller_id')->nullable();
            $table->string('remote_address')->nullable();
            $table->string('local_address')->nullable();
            $table->string('uptime')->nullable();
            $table->bigInteger('bytes_in')->nullable();
            $table->bigInteger('bytes_out')->nullable();
            $table->boolean('disabled')->default(false);
            $table->text('comment')->nullable();

            // Relasi ke MikrotikConnection
            $table->foreignId('mikrotik_id')
                  ->constrained('mikrotik_connections')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pppoe_users');
    }
};
