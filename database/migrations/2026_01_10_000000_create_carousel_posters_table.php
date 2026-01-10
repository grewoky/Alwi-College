<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('carousel_posters', function (Blueprint $table) {
            $table->id();
            $table->string('cloudinary_public_id')->index();
            $table->string('cloudinary_secure_url', 2048);
            $table->string('cloudinary_format', 20)->nullable();
            $table->string('cloudinary_original_filename')->nullable();
            $table->unsignedInteger('position')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carousel_posters');
    }
};
