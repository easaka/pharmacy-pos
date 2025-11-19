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
       Schema::create('sales', function (Blueprint $table) {
        $table->id();
        $table->string('invoice_no')->unique();
        $table->unsignedBigInteger('user_id')->nullable(); // cashier
        $table->decimal('total', 12, 2);
        $table->decimal('paid', 12, 2)->default(0);
        $table->string('payment_method')->nullable();
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
