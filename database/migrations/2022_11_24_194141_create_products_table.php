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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string('slug')->nullable(); //
            $table->string('code')->nullable()->unique(); //
            $table->string('image')->nullable(); //
            $table->string('video_url')->nullable(); //
            $table->bigInteger('rank')->default(0); //
            $table->tinyInteger('status')->default(1); //
            $table->tinyInteger('is_featured')->default(0);
            $table->unsignedBigInteger('created_by')->nullable(); //
            $table->unsignedBigInteger('updated_by')->nullable(); //
            $table->foreign('created_by')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('admins')->onDelete('set null');

            $table->decimal("selling_price")->default(0);
            $table->decimal("cost_price")->default(0);
            $table->decimal("discount")->default(0);

            $table->string("currency")->nullable();
            $table->integer("quantity")->default(0);;
            $table->integer("alert_stock_quantity")->default(0);
            $table->string("order_type");
            $table->text("short_description")->nullable();
            $table->text("long_description")->nullable();
            $table->text("return_policy")->nullable();
            $table->integer("rate")->default(0);
            $table->unsignedBigInteger("category_id")->nullable();
            $table->foreign("category_id")->references("id")->on("categories")->onDelete("cascade");
            $table->unsignedBigInteger("brand_id")->nullable();
            $table->foreign("brand_id")->references("id")->on("brands")->onDelete("cascade");

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
        Schema::dropIfExists('products');
    }
};
