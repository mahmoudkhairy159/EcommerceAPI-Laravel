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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string('slug')->nullable();
            $table->string('code')->unique()->nullable();
            $table->string("description", 1000);
            $table->string("main_category")->nullable();
            $table->string('image')->nullable();
            $table->integer("rate")->default(0);
            $table->foreignId('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('admins')->nullOnDelete();
            $table->foreignId('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('admins')->nullOnDelete();
            $table->tinyInteger('status')->default(1);
            $table->unsignedBigInteger("category_id")->nullable();
            $table->foreign("category_id")->references("id")->on("categories")->onDelete("cascade");
            $table->bigInteger('rank')->default(0); //

            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('services');
    }
};
