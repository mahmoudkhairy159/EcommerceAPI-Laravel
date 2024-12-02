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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->text('emails')->nullable();
            $table->text('faxes')->nullable();
            $table->text('phone_numbers')->nullable();
            $table->text('map_latitude')->nullable();
            $table->text('map_longitude')->nullable();
            $table->longText('map_iframe')->nullable();
            $table->text('facebook_link')->nullable();
            $table->text('instagram_link')->nullable();
            $table->text('twitter_link')->nullable();
            $table->text('behance_link')->nullable();
            $table->text('youtube_link')->nullable();
            $table->text('tiktok_link')->nullable();
            $table->text('linkedin_link')->nullable();
            $table->text('pinterest_link')->nullable();
            $table->text('patreon_link')->nullable();
            $table->text('google_maps_api_key')->nullable();
            $table->text('google_analytics_clint_id')->nullable();
            $table->text('google_recaptcha_api_key')->nullable();
            $table->tinyInteger('maintenance_mode')->default(0)->nullable();
            $table->string('logo')->nullable();
            $table->string('logo_light')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
