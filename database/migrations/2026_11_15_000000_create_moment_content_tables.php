<?php

use App\Services\MomentContent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('moment_settings', function (Blueprint $t) {
            $t->id();
            $t->json('data');
            $t->timestamps();
        });
        foreach (['moment_packages', 'moment_collections'] as $name) {
            Schema::create($name, function (Blueprint $t) {
                $t->id();
                $t->string('slug')->unique();
                $t->string('name');
                $t->json('data');
                $t->boolean('is_active')->default(true);
                $t->unsignedInteger('sort_order')->default(0);
                $t->timestamps();
            });
        }
        Schema::create('moment_media', function (Blueprint $t) {
            $t->id();
            $t->string('path')->unique();
            $t->string('alt_text');
            $t->unsignedInteger('width');
            $t->unsignedInteger('height');
            $t->unsignedTinyInteger('focus_x')->default(50);
            $t->unsignedTinyInteger('focus_y')->default(50);
            $t->timestamps();
        });
        MomentContent::initialize();
    }

    public function down(): void
    {
        foreach (['moment_media', 'moment_collections', 'moment_packages', 'moment_settings'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
