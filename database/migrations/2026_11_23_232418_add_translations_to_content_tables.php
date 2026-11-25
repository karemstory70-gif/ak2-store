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
        foreach ([
            'products',
            'categories',
            'banners',
            'storefront_pages',
            'product_images',
            'product_variants',
        ] as $tableName) {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->json('translations')->nullable();
            });
        }
    }

    /**
     * The columns may contain administrator-entered translations, so dropping
     * them during rollback would destroy content.
     */
    public function down(): void
    {
        throw new LogicException(
            'Translations may contain administrator content. Use a forward migration instead of rolling this migration back.',
        );
    }
};
