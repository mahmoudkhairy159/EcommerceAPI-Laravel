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
        Schema::create('setting_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('setting_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->string('slogan')->nullable();
            $table->longText('summary')->nullable();
            $table->text('address')->nullable();
            $table->foreign('setting_id')->references('id')->on('settings')->onDelete('cascade');
            $table->unique(['setting_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_translations');
    }
};
