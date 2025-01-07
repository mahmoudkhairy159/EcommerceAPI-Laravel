<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
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
            $table->string('slug')->nullable()->index();
            $table->string('code')->nullable()->unique();
            $table->text('seo_description')->nullable();
            $table->text('seo_keys')->nullable();
            $table->string('image')->nullable();
            $table->text('video_url')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable()->index();
            $table->unsignedBigInteger('category_id')->nullable()->index();
            $table->unsignedBigInteger('brand_id')->nullable()->index();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('set null');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');

            $table->double("price", 10, 2);
            $table->double("offer_price", 10, 2)->nullable()->default(0);
            $table->date('offer_start_date')->nullable();
            $table->date('offer_end_date')->nullable();

            $table->string("currency", 10)->default('USD'); // Add length limit
            $table->unsignedInteger("quantity")->default(1);
            $table->unsignedInteger("alert_stock_quantity")->default(0);

            $table->text("short_description")->nullable();
            $table->longText("long_description")->nullable();
            $table->longText("return_policy")->nullable();

            $table->boolean('is_featured')->default(0);
            $table->boolean('is_top')->default(0);
            $table->boolean('is_best')->default(0);
            $table->tinyInteger('approval_status')->default(0);

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->foreign('created_by')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('admins')->onDelete('set null');

            $table->tinyInteger('status')->default(1);
            $table->unsignedInteger('serial')->default(1);

            $table->timestamps();
            $table->softDeletes(); // Enable soft deletes

            // Adding composite indexes
            $table->index(['category_id', 'brand_id']);

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
