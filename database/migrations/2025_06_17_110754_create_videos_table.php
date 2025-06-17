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
         Schema::create('videos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('filename');
            $table->enum('format', ['hls', 'dash'])->index();
            $table->enum('drm', ['widevine', 'fairplay', 'playready'])->index();
            $table->timestamp('uploaded_at')->useCurrent();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};