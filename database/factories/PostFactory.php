<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Club;
use App\Models\User;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate a random likes configuration
        $likedUsers = fake()->optional(0.8, [])->randomElements(range(1, 50), fake()->numberBetween(0, 25));
        $likesCount = count($likedUsers);

        // Pick a random number for how many comments this post should get
        $commentsCount = fake()->numberBetween(0, 5);

        return [
            'club_id'        => \App\Models\Club::inRandomOrder()->first()?->id ?? \App\Models\Club::factory(),
            'user_id'        => \App\Models\User::inRandomOrder()->first()?->id ?? \App\Models\User::factory(),
            'title'          => rtrim(fake()->realTextBetween(15, 40), '.'), 
            'content'        => fake()->realTextBetween(50, 200), 
            'image'          => fake()->optional(0.7)->imageUrl(640, 480, 'posts', true),
            
            'likes_count'    => $likesCount,
            'liked_users'    => $likedUsers, 
            
            // Save the count directly so your column matches perfectly
            'comments_count' => $commentsCount, 
        ];
    }

    /**
     * Configure the model factory hooks.
     */
    public function configure()
    {
        return $this->afterCreating(function (\App\Models\Post $post) {
            // Look at the random count we generated for this post row
            if ($post->comments_count > 0) {
                // Create real database rows linked directly to this post!
                \App\Models\PostComment::factory()
                    ->count($post->comments_count)
                    ->create(['post_id' => $post->id]);
            }
        });
    }
}