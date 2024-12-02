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
        Schema::create('reference_customers', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string('slug')->nullable();
            $table->string("image")->nullable();
            $table->timestamps();
            $table->foreignId('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('admins')->nullOnDelete();
            $table->foreignId('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('admins')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reference_customers');
    }
};
