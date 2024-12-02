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
            $table->dateTime('order_date');
            $table->enum('status', Order::getStatuses())->default(Order::STATUS_PENDING);
            $table->enum('payment_method', Order::getPaymentMethods())->nullable();
            $table->decimal('discount_amount', 8, 2)->default(0);
            $table->decimal('total_price', 8, 2)->default(0);
            $table->decimal('tax', 10, 2)->nullable();
            $table->text('notes')->nullable();
            //
            $table->string("tracking_id")->nullable();
            $table->string("order_type");
            $table->string("state");
            $table->string("city");
            $table->string("pin_code");
            $table->text("billing_address");
            $table->string("order_phone_number");
            //
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
