
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();

            // Banner content
            $table->string('title', 200)->nullable();
            $table->text('subtitle')->nullable();

            // Banner images
            $table->string('image_path', 500);
            $table->string('mobile_image_path', 500)->nullable();
            $table->string('alt_text', 255)->nullable();

            // Call to action
            $table->string('button_text', 100)->nullable();
            $table->string('button_url', 2048)->nullable();

            // Display settings
            $table->string('placement', 60)
                ->default('homepage_hero');

            $table->boolean('is_active')->default(false);
            $table->unsignedInteger('sort_order')->default(0);

            // Scheduling
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            // Performance indexes
            $table->index([
                'placement',
                'is_active',
                'sort_order'
            ]);

            $table->index([
                'starts_at',
                'ends_at'
            ]);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
