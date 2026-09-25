<?php

namespace Database\Factories;

use App\Models\Facility;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Facility>
 */
class FacilityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'name' => Str::title($name),
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 9999),
            'type' => fake()->randomElement(['facility', 'accommodation', 'activity', 'dining']),
            'short_description' => fake()->sentence(),
            'description' => fake()->paragraphs(3, true),
            'capacity' => fake()->numberBetween(2, 200),
            'price' => fake()->randomFloat(2, 20, 800),
            'price_unit' => fake()->randomElement(['per person', 'per night', 'per group', 'per hour']),
            'amenities' => fake()->randomElements(['WiFi', 'Parking', 'Air-conditioning', 'BBQ Pit', 'Sound System', 'Lighting'], 3),
            'is_featured' => fake()->boolean(30),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
