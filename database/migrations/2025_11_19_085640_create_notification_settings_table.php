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
        Schema::create('notification_settings', function (Blueprint $table) {
        $table->id();
        $table->integer('low_stock_threshold')->default(5);
        $table->integer('expiry_warning_days')->default(30);
        $table->boolean('email_alerts')->default(true);
        $table->boolean('sms_alerts')->default(false);
        $table->boolean('popup_alerts')->default(true);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};
