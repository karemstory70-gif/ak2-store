<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();

            // Parent product
            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            // Variant identification
            $table->string('sku', 100)->unique();

            // Size and color
            $table->string('size', 50)->nullable();
            $table->string('color', 100)->nullable();
            $table->string('color_code', 20)->nullable();

            // Pricing and inventory
            $table->decimal('price', 10, 2)->nullable();
            $table->unsignedInteger('stock_quantity')->default(0);

            // Availability
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['product_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
