<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('community_images', function (Blueprint $table) {
            $table->id();
            $table->string('image_path');
            $table->string('alt_text');
            $table->string('caption')->nullable();
            $table->unsignedInteger('width');
            $table->unsignedInteger('height');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });

        $now = now();

        DB::table('community_images')->insert([
            [
                'image_path' => 'images/community/1.png',
                'alt_text' => 'Woman wearing a black hijab and black-and-white jacket in a modern interior',
                'caption' => 'Style Inspiration',
                'width' => 294,
                'height' => 392,
                'sort_order' => 0,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'image_path' => 'images/community/2.png',
                'alt_text' => 'Man in black clothing standing in a warmly lit cafe',
                'caption' => 'Fashion Forward',
                'width' => 696,
                'height' => 392,
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'image_path' => 'images/community/3.png',
                'alt_text' => 'Man wearing a black track jacket seated on stairs',
                'caption' => 'Street Style',
                'width' => 307,
                'height' => 546,
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'image_path' => 'images/community/4.png',
                'alt_text' => 'Man wearing a black-and-white jacket outdoors at sunset',
                'caption' => 'Urban Vibes',
                'width' => 696,
                'height' => 391,
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('community_images');
    }
};
