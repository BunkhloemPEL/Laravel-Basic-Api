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
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'user_id')->constrained(table: 'users')->onDelete(action: 'cascade');
            $table->foreignId(column: 'category_id')->constrained(table: 'blog_categories')-> onDelete(action: 'cascade');
            $table->string(column: 'title');
            $table->string(column: 'slug');
            $table->text(column: 'content');
            $table->text(column: 'excerpt')->nullable();
            $table->string(column: 'thumnail')->nullable();
            $table->enum(column: 'status', allowed: ['draft', 'published', 'archieved'])->draft();
            $table->dateTime(column: 'published_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
