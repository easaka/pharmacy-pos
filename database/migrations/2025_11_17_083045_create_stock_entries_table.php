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
         Schema::create('stock_entries', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('product_id');
        $table->integer('quantity')->default(0);
        $table->string('batch_number')->nullable();
        $table->date('expiry_date')->nullable();
        $table->string('type')->default('in'); // in | out | adjustment
        $table->unsignedBigInteger('created_by')->nullable();
        $table->text('note')->nullable();
        $table->timestamps();

        $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_entries');
    }
};
