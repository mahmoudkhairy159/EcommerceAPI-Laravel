<?php

use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            //

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->enum('status', Order::getStatuses())->default(Order::STATUS_PENDING);
            $table->enum('payment_method', Order::getPaymentMethods())->nullable();
            $table->enum('payment_status', Order::getPaymentStatuses())->nullable();
            $table->decimal('sub_total', 8, 2)->default(0);
            $table->decimal('discount_amount', 8, 2)->default(0);
            $table->decimal('amount', 8, 2)->default(0);
            $table->text('order_address')->nullable();
            $table->text('shipping_rule')->nullable();
            $table->text('coupon')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps(); //

            ///

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
