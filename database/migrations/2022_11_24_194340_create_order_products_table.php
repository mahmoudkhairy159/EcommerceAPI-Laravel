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
            $table->unsignedBigInteger("vendor_id")->nullable();
            $table->foreign("vendor_id")->references("id")->on("vendors")->onDelete("set null");
            $table->unsignedBigInteger("product_id")->nullable();
            $table->foreign("product_id")->references("id")->on("products")->onDelete("cascade");
            $table->double("price", 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->integer("quantity");
            $table->text('variants')->nullable();
            $table->double("variantsTotalPrice", 10, 2);



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
