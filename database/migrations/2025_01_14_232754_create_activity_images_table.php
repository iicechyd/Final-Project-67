<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_images', function (Blueprint $table) {
            $table->id('image_id');
            $table->unsignedBigInteger('activity_id');
            $table->foreign('activity_id')->references('activity_id')->on('activities')->onDelete('cascade');
            $table->string('image_path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_images');
    }
};
