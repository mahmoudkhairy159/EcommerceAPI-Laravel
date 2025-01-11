<?php

use App\Enums\DiscountTypeEnum;
use App\Enums\ProductTypeEnum;
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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string('code')->unique();
            $table->unsignedInteger("quantity")->default(1);
            $table->unsignedInteger("max_use")->default(1)->comment('max use per one person');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('discount_type',DiscountTypeEnum::getConstants())->default(DiscountTypeEnum::PERCENTAGE);
            $table->unsignedInteger('discount')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->foreign('created_by')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('admins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
