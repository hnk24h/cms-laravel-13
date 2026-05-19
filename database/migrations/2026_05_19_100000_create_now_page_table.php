<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('now_page', function (Blueprint $table) {
            $table->id();
            $table->string('location')->nullable();
            $table->text('status')->nullable();          // Đang làm gì / tổng quan
            $table->json('focus')->nullable();           // [{text, icon}]
            $table->json('reading')->nullable();         // [{title, author, type, url}]
            $table->json('learning')->nullable();        // [{text}]
            $table->json('vocabulary')->nullable();      // [{word, reading, meaning, type}]
            $table->boolean('published')->default(true);
            $table->timestamp('content_updated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('now_page');
    }
};
