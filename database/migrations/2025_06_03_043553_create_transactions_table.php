<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id'); // id Utama int(11) AUTO_INCREMENT
            $table->unsignedInteger('category_id'); // foreign key ke categories
            $table->text('description')->nullable();
            $table->decimal('amount', 12, 2);
            $table->enum('transaction_type', ['income', 'expense']);
            $table->date('transaction_date');
            $table->timestamps(); // created_at & updated_at

            // Relasi ke categories table
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
}
