<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(), //just another way to create a user and get its id. It could be made via use App\Models\User; and then User::factory() but this is also fine. Much more contextual to me actually.
            'book_id' => \App\Models\Book::factory(), //same as above but for book. This will create a book and get its id.
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->paragraph(),
            //
        ];
    }
}
