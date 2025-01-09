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
        Schema::create('flash_sale_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flash_sale_id')->nullable();
            $table->foreign('flash_sale_id')->references('id')->on('flash_sales')->onDelete('cascade');
            $table->foreignId('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->boolean('show_at_home')->default(0);
            $table->boolean('status')->default(value: 1);
            $table->timestamps();
            $table->unique(['flash_sale_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flash_sale_products');
    }
};
