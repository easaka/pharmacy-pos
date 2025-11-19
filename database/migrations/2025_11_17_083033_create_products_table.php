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
         Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('sku')->nullable()->unique(); // optional barcode/SKU
        $table->string('name');
        $table->unsignedBigInteger('category_id')->nullable();
        $table->unsignedBigInteger('supplier_id')->nullable();
        $table->decimal('cost_price', 12, 2)->default(0);
        $table->decimal('selling_price', 12, 2)->default(0);
        $table->integer('reorder_level')->default(0);
        $table->text('description')->nullable();
        $table->timestamps();

        $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();
        $table->foreign('supplier_id')->references('id')->on('suppliers')->nullOnDelete();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
