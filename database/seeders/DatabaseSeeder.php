<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory()->create(
                    [
            'name' => 'Admin User',
            'email' => 'admin@pageturner.com',
            'role' => 'admin',
        ]
        );

        // Create customer users
        $customers = User::factory(10)->create([
            'role' => 'customer',
        ]);
        //create categories
        $categories = \App\Models\Category::factory(8)->create();

        //create 5 books for each category
        $categories->each(function ($category) {
        \App\Models\Book::factory(5)->create(['category_id' => $category->id]);
        });

        //create reviews
        $books = \App\Models\Book::all(); //get all books

        foreach ($customers as $customer){
            $booksToReview = $books->random(rand(3, 5));
            foreach ($booksToReview as $book){
                //The if statement is to prevent duplicate reviews for the same book by the same user. Since we are creating reviews in a loop, it is possible that the same user could review the same book multiple times if it is randomly selected again. This check ensures that each user can only review a specific book once, maintaining data integrity and preventing unrealistic scenarios in the seeded data.
            if(!\App\Models\Review::where('user_id', $customer->id)->where('book_id', $book->id)->exists()){
                    \App\Models\Review::factory()->create([
                            'user_id' => $customer->id,
                            'book_id' => $book->id,
                        ]);
                }
            }
        }

    }
}
