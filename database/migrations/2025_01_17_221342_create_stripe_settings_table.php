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
        Schema::create('stripe_settings', function (Blueprint $table) {
            $table->id();
            $table->string('client_id')->nullable(); // PayPal Client ID
            $table->string('client_secret')->nullable(); // PayPal Secret Key
            $table->string('app_id')->nullable(); // PayPal app_id
            $table->enum('mode', ['sandbox', 'production'])->default('sandbox');
            $table->enum('Payment_action', ['Sale', 'Authorization','Order'])->default('Sale');
            $table->string('currency', 3)->default('USD'); // Default currency
            $table->string('notify_url')->nullable();
            $table->string('locale')->default('en_US');
            $table->boolean('validate_ssl')->default(false);
            $table->tinyInteger('status')->default(1); // Whether PayPal is active
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stripe_settings');
    }
};
