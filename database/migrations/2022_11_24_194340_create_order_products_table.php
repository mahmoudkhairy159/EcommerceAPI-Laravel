<?php

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
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("order_id");
            $table->foreign("order_id")->references("id")->on("orders")->onDelete("cascade");
            $table->unsignedBigInteger("product_id")->nullable();
            $table->foreign("product_id")->references("id")->on("products")->onDelete("cascade");

            $table->decimal("selling_price");
            $table->decimal("cost_price");
            $table->integer("quantity");
            $table->decimal("discount")->nullable();
            $table->text("return_policy")->nullable();
            // Ensure combination of order_id and product_id is unique
            $table->unique(['order_id', 'product_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_products');
    }
};
