<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            'Fiction',
            'Non-Fiction',
            'Science',
            'Technology',
            'Biography',
            'History',
            'Romance',
            'Mystery',
            'Self-Help',
            'Children',
        ];
        return [
            //This is sus.
            // This is to create unique categories, but it is not ideal. We should ideally have a seeder that creates these categories and then reference them here. - is waht the AI suggests. Seeders in Laravel are classes that populate your database with test or initial data. They're like "seed" data that you "plant" in your database.
            'name'=> fake()->unique()->randomElement($categories),
            'description'=> fake()->paragraph(),
        ];
    }
}
