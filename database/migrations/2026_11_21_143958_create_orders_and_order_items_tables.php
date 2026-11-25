<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Order identification
            $table->string('order_number')->unique();

            // Customer
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone');

            // Shipping address
            $table->string('shipping_address');
            $table->string('shipping_city');
            $table->string('shipping_state')->nullable();
            $table->string('shipping_postal_code')->nullable();
            $table->string('shipping_country')->default('Egypt');

            // Financial details
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('shipping_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);

            $table->string('coupon_code')->nullable();
            $table->string('currency', 3)->default('EGP');

            // Order and payment status
            $table->string('status')->default('pending')->index();
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('pending')->index();
            $table->string('transaction_id')->nullable();

            // Additional information
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();

            $table->timestamp('placed_at')->nullable();

            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

            // Keep order history even if product is deleted
            $table->foreignId('product_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('product_variant_id')
                ->nullable()
                ->constrained('product_variants')
                ->nullOnDelete();

            // Product snapshot at purchase time
            $table->string('product_name');
            $table->string('product_sku')->nullable();
            $table->string('product_image')->nullable();
            $table->string('variant_details')->nullable();

            // Price snapshot
            $table->decimal('unit_price', 12, 2);
            $table->unsignedInteger('quantity');
            $table->decimal('total', 12, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};