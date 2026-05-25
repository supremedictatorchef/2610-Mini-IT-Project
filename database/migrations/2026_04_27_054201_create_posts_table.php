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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->string('title');
            $table->text('content');
            $table->string('image')->nullable();

            // ✅ Counts
            $table->unsignedInteger('likes_count')->default(0);    
            $table->unsignedInteger('comments_count')->default(0); 

            // ✅ Store actual data inside posts table
            $table->json('liked_users')->nullable();   // array of user IDs who liked
            $table->json('comments')->nullable();      // array of comment objects

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
