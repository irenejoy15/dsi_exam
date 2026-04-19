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
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->unsignedBigInteger('category_id');
            $table->integer('stock')->default(0);
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }
    // php artisan migrate --path=database/migrations/2026_04_14_204821_create_categories_table.php
    // php artisan migrate:refresh --path=database/migrations/0001_01_01_000000_create_users_table.php
    // php artisan migrate:refresh --path=database/migrations/2026_04_14_204811_create_products_table.php
    // php artisan migrate --path=database/migrations/2026_04_16_143149_create_security_answers_table.php
    // php artisan migrate --path=database/migrations/2026_04_17_135039_create_carts_table.php
    // php artisan migrate --path=database/migrations/2026_04_18_175536_create_orders_table.php
    // php artisan migrate --path=database/migrations/2026_04_18_175548_create_order_items_table.php
    // composer require laravel/ui
    // php artisan ui bootstrap --auth
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
