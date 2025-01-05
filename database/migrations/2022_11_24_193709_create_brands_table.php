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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string('slug')->nullable();
            $table->unsignedBigInteger("category_id")->nullable();
            $table->foreign("category_id")->references("id")->on("categories")->onDelete("cascade");
            $table->string('image1')->nullable();
            $table->string('image2')->nullable(); // Added image1
            $table->text('short_description')->nullable(); // Added short_description
            $table->text('long_description')->nullable(); // Added long_description
            $table->tinyInteger('long_description_status')->default(1);
            $table->text('brief')->nullable();
            $table->string('code')->unique()->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('admins')->nullOnDelete();
            $table->foreignId('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('admins')->nullOnDelete();
            $table->tinyInteger('status')->default(1);
            $table->bigInteger('serial')->default(1); //
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
        Schema::table('brands', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('brands');
    }
};
