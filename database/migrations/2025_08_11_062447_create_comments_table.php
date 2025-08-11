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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'post_id')->constrained(table: 'blog_posts')->onDelete(action: 'cascade');
            $table->foreignId(column: 'user_id')->constrained(table: 'users')->onDelete(action: 'cascade');
            $table->integer(column: 'parent_id')->default(value: 0);
            $table->text(column: 'content');
            $table->enum(column: 'status', allowed:['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
