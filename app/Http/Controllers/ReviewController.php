<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use App\Http\Requests\StoreReviewRequest;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReviewRequest $request, Book $book)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();//false positive from intelliphense,
        $validated['book_id'] = $book->id;

        // Check if user already reviewed this book
        $existingReview = Review::where('user_id', auth()->id())//false positive from intelliphense,
            ->where('book_id', $book->id)
            ->first();

        if ($existingReview) {
            $existingReview->update($validated);
            $message = 'Review updated successfully!';
        } else {
            Review::create($validated);
            $message = 'Review submitted successfully!';
        }

        return redirect()->route('books.show', $book)
            ->with('success', $message);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book, Review $review)
    {
        // Check authorization
        if (auth()->id() !== $review->user_id) {//false positive from intelliphense,
            abort(403);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update($validated);

        return redirect()->route('books.show', $book)
            ->with('success', 'Review updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book, Review $review)
    {
        // Check authorization
        if (auth()->id() !== $review->user_id && !optional(auth()->user())->isAdmin()) {//false positive from intelliphense,
            abort(403);
        }

        $review->delete();

        return redirect()->route('books.show', $book)
            ->with('success', 'Review deleted successfully!');
    }
}
