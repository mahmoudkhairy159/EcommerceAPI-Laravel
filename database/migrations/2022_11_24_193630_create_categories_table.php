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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string("name")->unique();
            $table->string('slug')->nullable();
            $table->string("description", 1000)->nullable();
            $table->string('image')->nullable();
            $table->string('code')->unique()->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('admins')->nullOnDelete();
            $table->foreignId('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('admins')->nullOnDelete();
            $table->tinyInteger('status')->default(1)->comment('1=Active, 0=Inactive');
            $table->bigInteger('serial')->default(1)->unsigned()->comment('Used for ordering categories');
            $table->unsignedBigInteger('parent_id')->nullable(); // Self-referencing column
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            $table->index('parent_id');
            $table->index('slug');
            $table->index('status');
            $table->index('serial');

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
            $table->dropForeign(['parent_id']);
            $table->dropIndex(['parent_id']);
            $table->dropIndex(['slug']);
            $table->dropIndex(['status']);
            $table->dropIndex(['serial']);
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('categories');
    }
};
