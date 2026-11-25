<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Category relationship
            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();

            // Basic product information
            $table->string('name', 200);
            $table->string('slug', 220)->unique();
            $table->string('sku', 100)->unique();
            $table->text('description')->nullable();

            // Pricing
            $table->decimal('price', 10, 2);
            $table->decimal('compare_at_price', 10, 2)->nullable();

            // Product status
            $table->boolean('is_active')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();

            // Search and listing performance
            $table->index(['is_active', 'is_featured']);
            $table->index('category_id');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
