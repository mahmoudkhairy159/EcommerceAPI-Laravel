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
        Schema::create('cart_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cart_id');
            $table->unsignedBigInteger('product_id');
            $table->string('name');
            $table->integer('quantity')->default(1)->check('quantity >= 1'); // Minimum quantity constraint
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0); // Precomputed subtotal (price * quantity + tax)
            $table->json('options')->nullable();
            $table->timestamp('expires_at')->nullable(); // Expiration date for the cart

            $table->timestamps();

            // Foreign Keys
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            // Indexes for performance
            $table->index(['cart_id', 'product_id']); // Composite index for frequent cart queries
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_products', function (Blueprint $table) {
            $table->dropIndex(['cart_id', 'product_id']); // Drop composite index
        });

        Schema::dropIfExists('cart_products');
    }
};
