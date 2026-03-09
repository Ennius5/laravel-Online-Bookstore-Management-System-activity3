<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Book::query()->with('category', 'reviews');

        // Search with type selection
        if ($request->filled('search')) {
            $search = $request->search;
            $searchType = $request->get('search_type', 'both');

            if ($searchType === 'title') {
                $query->where('title', 'LIKE', "%{$search}%");
            } elseif ($searchType === 'author') {
                $query->where('author', 'LIKE', "%{$search}%");
            } else { // both
                $query->where(function($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                      ->orWhere('author', 'LIKE', "%{$search}%");
                });
            }
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'rating_low':
                    // Get books with review data and sort by average rating
                    $query->withAvg('reviews', 'rating')
                          ->orderBy('reviews_avg_rating', 'asc');
                    break;
                case 'rating_high':
                    $query->withAvg('reviews', 'rating')
                          ->orderBy('reviews_avg_rating', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'title_asc':
                    $query->orderBy('title', 'asc');
                    break;
                case 'title_desc':
                    $query->orderBy('title', 'desc');
                    break;
                default:
                    // Default sorting by newest
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            // Default sorting
            $query->orderBy('created_at', 'desc');
        }

        // Pagination with per page
        $perPage = $request->get('per_page', 12);
        $books = $query->paginate($perPage)->withQueryString();

        // Get all categories for the filter dropdown
        $categories = Category::all();

        return view('books.index', compact('books', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('books.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')
                ->store('book-covers', 'public');
        }

        Book::create($validated);

        return redirect()->route('books.index')
            ->with('success', 'Book created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book->load(['category', 'reviews.user']);
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        $categories = Category::all();
        return view('books.edit', compact('book', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'isbn' => 'required|string|unique:books,isbn,' . $book->id,
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image removal
        if ($request->has('remove_cover_image') && $request->remove_cover_image == '1') {
            // Delete the old image file
            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $validated['cover_image'] = null;
        } else {
            // Keep the existing image if no new one is uploaded
            $validated['cover_image'] = $book->cover_image;
        }

        // Handle new image upload
        if ($request->hasFile('cover_image')) {
            // Delete old image if exists
            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }

            // Store new image
            $path = $request->file('cover_image')->store('book-covers', 'public');
            Log::info("New cover image uploaded for book ID {$book->id}: {$path}");
            $validated['cover_image'] = $path;
        }

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Book updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        // Delete cover image if exists
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Book deleted successfully.');
    }

    public function getBooks($categoryId)
    {
        // Find the category with its books
        $category = Category::with('books')->findOrFail($categoryId);

        // Return just the books or full category data
        return response()->json([
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
            ],
            'books' => $category->books->map(function($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author,
                    'isbn' => $book->isbn,
                    'description' => $book->description,
                    'cover_image' => $book->cover_image,
                    'cover_image_url' => $book->cover_image_url,
                    'average_rating' => $book->average_rating,
                ];
            })
        ]);
    }


}
