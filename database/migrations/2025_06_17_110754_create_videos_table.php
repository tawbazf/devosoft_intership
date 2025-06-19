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
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

        $table->string('title');
        $table->text('description')->nullable();

        $table->string('filename');
        $table->string('path');
        
        $table->enum('format', ['hls', 'dash'])->index();
        $table->enum('drm', ['widevine', 'fairplay', 'playready'])->index();

        $table->string('manifest_url')->nullable();
        $table->string('license_url')->nullable();

        $table->enum('status', ['pending', 'packaged', 'error'])->default('pending');
        $table->unsignedInteger('views')->default(0);

        $table->timestamp('uploaded_at')->useCurrent();
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