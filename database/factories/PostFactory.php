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
        // 1. Generate a completely random array of user IDs first (between 0 and 25 users)
        $likedUsers = fake()->optional(0.8, [])->randomElements(
            range(1, 50), 
            fake()->numberBetween(0, 25)
        );

        // 2. Set the count based on how many users are ACTUALLY in that array
        $likesCount = count($likedUsers);

        // 3. Do the exact same thing for comments to keep it safe
        $commentsCount = fake()->numberBetween(0, 5);
        $comments = [];
        for ($i = 0; $i < $commentsCount; $i++) {
            $comments[] = [
                'user_id'    => fake()->numberBetween(1, 50),
                'comment'    => fake()->sentence(),
                'created_at' => fake()->dateTimeThisMonth()->format('Y-m-d H:i:s'),
            ];
        }

        return [
            'club_id'        => Club::inRandomOrder()->first()?->id ?? Club::factory(),
            'user_id'        => User::inRandomOrder()->first()?->id ?? User::factory(),
            
            
            // Generates real English phrases between 15 and 40 characters long
            'title' => rtrim(fake()->realTextBetween(15, 40), '.'), // rtrim removes the trailing period
            // 🇬🇧 Generates clean, cohesive paragraphs of actual English book text
            'content'        => fake()->realTextBetween(50, 200), 
            
            'image'          => fake()->optional(0.7)->imageUrl(640, 480, 'posts', true),
            'likes_count'    => $likesCount,
            'comments_count' => $commentsCount,
            'liked_users'    => $likedUsers, 
            'comments'       => $comments,
        ];
    }
}