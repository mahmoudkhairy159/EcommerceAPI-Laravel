<?php

use App\Models\Transaction;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('user_id')->nullable();  // If a user initiated the transaction
            $table->string('transaction_id')->nullable();

            $table->decimal('amount', 10, 2);  // Transaction amount
            $table->enum('payment_method', Transaction::getPaymentMethods())->nullable();
            $table->enum('payment_status', Transaction::getPaymentStatuses())->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
