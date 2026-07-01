<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Club;

class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [ 
            'title' => ucwords(fake()->bs()), // Generates catchy, buzzword-heavy titles
            'description' => fake()->optional()->paragraph(), // 1in5 chance of being null, otherwise text
            'date'        => fake()->dateTimeBetween('now', '+6 months')->format('Y-m-d'), // Format for date column
            'time'        => fake()->time('H:i'), // Format like "14:30"
            'location'    => fake()->optional()->address(), // Generates a mock address string
            
            // Simulates a tiny, mock JSON array of file paths
            'uploads'     => fake()->optional()->randomElement([
                json_encode(['images/event1.jpg', 'docs/agenda.pdf']),
                json_encode(['images/poster.png']),
                null
            ]),

            // Dynamically assigns a random existing club ID
            'club_id'     => Club::inRandomOrder()->first()?->id ?? Club::factory(),
            
            'deleted_at'  => null, // Keeps it active by default
        ];
    }
}