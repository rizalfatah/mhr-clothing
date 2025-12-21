<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Clear existing cart items to avoid conflicts
        DB::table('cart_items')->truncate();

        // Drop the table and recreate it with the new structure
        Schema::dropIfExists('cart_items');

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->onDelete('cascade');
            $table->integer('quantity')->unsigned()->default(1);
            $table->timestamps();

            // Unique constraint including variant
            $table->unique(['user_id', 'product_id', 'product_variant_id'], 'cart_items_user_product_variant_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop and recreate with old structure
        Schema::dropIfExists('cart_items');

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->unsigned()->default(1);
            $table->timestamps();

            // Old unique constraint
            $table->unique(['user_id', 'product_id']);
        });
    }
};
