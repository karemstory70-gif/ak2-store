<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('storefront_pages', function (Blueprint $table): void {
            $table->id();
            $table->string('slug', 64)->unique();
            $table->string('title', 200);
            $table->string('meta_title', 200)->nullable();
            $table->string('meta_description', 320)->nullable();
            $table->string('eyebrow', 120)->nullable();
            $table->string('heading', 200)->nullable();
            $table->text('subheading')->nullable();
            $table->longText('body')->nullable();
            $table->string('hero_image_path', 500)->nullable();
            $table->string('primary_cta_text', 100)->nullable();
            $table->string('primary_cta_url', 2048)->nullable();
            $table->json('sections')->nullable();
            $table->boolean('is_published')->default(false)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storefront_pages');
    }
};
