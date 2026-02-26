@extends('layouts.app')

@section('title', $book->title . ' - PageTurner')

@section('content')
    {{-- Book Details --}}
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="md:flex">
            {{-- Book Cover --}}
            <div class="md:w-1/3 bg-gray-100 p-8 flex items-center justify-center">
                @if($book->cover_image)
                    <img
                        src="{{ asset('storage/' . $book->cover_image) }}"
                        alt="{{ $book->title }}"
                        class="max-h-96 object-contain rounded-lg shadow-md"
                    >
                @else
                    <div class="text-gray-400">
                        <svg class="h-48 w-48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                        <p class="text-center mt-4">No Cover Image</p>
                    </div>
                @endif
            </div>

            {{-- Book Details --}}
            <div class="md:w-2/3 p-8">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="inline-block bg-indigo-100 text-indigo-800 text-sm font-medium px-3 py-1 rounded-full">
                            {{ $book->category->name ?? 'Uncategorized' }}
                        </span>
                    </div>
                    @if($book->stock_quantity <= 5 && $book->stock_quantity > 0)
                        <span class="text-sm bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full">
                            Only {{ $book->stock_quantity }} left!
                        </span>
                    @endif
                </div>

                <h1 class="text-3xl font-bold text-gray-900 mt-4">{{ $book->title }}</h1>
                <p class="text-xl text-gray-600 mt-2">by {{ $book->author }}</p>

                {{-- Rating --}}
                <div class="flex items-center mt-4">
                    @php
                        $averageRating = $book->average_rating ?? 0;
                        $fullStars = floor($averageRating);
                        $hasHalfStar = ($averageRating - $fullStars) >= 0.5;
                    @endphp

                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $fullStars)
                            <svg class="h-6 w-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @elseif($i == $fullStars + 1 && $hasHalfStar)
                            <svg class="h-6 w-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <defs>
                                    <linearGradient id="half-gradient">
                                        <stop offset="50%" stop-color="currentColor"/>
                                        <stop offset="50%" stop-color="#D1D5DB"/>
                                    </linearGradient>
                                </defs>
                                <path fill="url(#half-gradient)"
                                      d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @else
                            <svg class="h-6 w-6 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endif
                    @endfor
                    <span class="ml-2 text-gray-600">
                        {{ $averageRating > 0 ? number_format($averageRating, 1) : 'No ratings' }}
                        @if($book->reviews->count() > 0)
                            ({{ $book->reviews->count() }} review{{ $book->reviews->count() !== 1 ? 's' : '' }})
                        @endif
                    </span>
                </div>

                {{-- Price and Stock --}}
                <div class="mt-6 flex items-center justify-between border-t border-b border-gray-200 py-4">
                    <div>
                        <p class="text-3xl font-bold text-indigo-600">${{ number_format($book->price, 2) }}</p>
                        <div class="mt-2">
                            <span class="{{ $book->stock_quantity > 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                                @if($book->stock_quantity > 0)
                                    <svg class="h-5 w-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    In Stock ({{ $book->stock_quantity }} available)
                                @else
                                    <svg class="h-5 w-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Out of Stock
                                @endif
                            </span>
                        </div>
                    </div>




                    {{--Button to order--}}
                    @if($book->stock_quantity > 0)
                        {{-- <form action="{{ route('orders.store', $book) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            <input type="number" name="quantity" value="1" min="1" max="{{ $book->stock_quantity }}"
                                   class="w-20 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition font-medium">
                                Add to Cart
                            </button>
                        </form> --}}
                        {{--FORM FIX -->  --}}
                         <form action="{{ route('orders.store', $book) }}" method="POST" class="flex items-center gap-2">
                        @csrf
                            {{-- Hidden field to pass book_id in the order_items array structure --}}
                            <input type="hidden" name="order_items[0][book_id]" value="{{ $book->id }}">

                            <input type="number"
                                name="order_items[0][quantity]"
                                value="1"
                                min="1"
                                max="{{ $book->stock_quantity }}"
                                class="w-20 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">

                            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition font-medium">
                                Add to Cart
                            </button>
                        </form>
                    @endif
                </div>

                {{-- Book Info --}}
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600"><strong>ISBN:</strong> {{ $book->isbn }}</p>
                        <p class="text-gray-600 mt-2"><strong>Published:</strong>
                            {{ $book->published_date ? $book->published_date->format('F Y') : 'Not specified' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-600"><strong>Pages:</strong>
                            {{ $book->pages ?? 'Not specified' }}
                        </p>
                        <p class="text-gray-600 mt-2"><strong>Language:</strong>
                            {{ $book->language ?? 'English' }}
                        </p>
                    </div>
                </div>

                {{-- Description --}}
                <div class="mt-8">
                    <h3 class="font-semibold text-xl text-gray-800 mb-4">Description</h3>
                    <div class="text-gray-600 leading-relaxed prose max-w-none">
                        {!! nl2br(e($book->description ?? 'No description available.')) !!}
                    </div>
                </div>

                {{-- Admin Actions --}}
                @auth
                    @if(auth()->user()->isAdmin())
                        <div class="mt-8 pt-6 border-t border-gray-200 flex space-x-4">
                            <a href="{{ route('admin.books.edit', $book) }}"
                               class="inline-flex items-center bg-yellow-500 text-white px-5 py-2.5 rounded-lg hover:bg-yellow-600 transition font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Book
                            </a>
                            <form action="{{ route('admin.books.destroy', $book) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this book?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center bg-red-500 text-white px-5 py-2.5 rounded-lg hover:bg-red-600 transition font-medium">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete Book
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    {{-- Reviews Section --}}
    <div class="mt-12">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Customer Reviews</h2>
            @if($book->reviews->count() > 0)
                <span class="text-gray-600">
                    {{ $book->reviews->count() }} review{{ $book->reviews->count() !== 1 ? 's' : '' }}
                </span>
            @endif
        </div>

        {{-- Review Form (for authenticated users) --}}
        @auth
            <div class="bg-white rounded-2xl shadow p-6 mb-8">
                <h3 class="font-semibold text-lg text-gray-900 mb-4">Write a Review</h3>
                <form action="{{ route('reviews.store', $book) }}" method="POST">
                    @csrf

                    {{-- Check if user already reviewed --}}
                    @php
                        $userReview = $book->reviews->where('user_id', auth()->id())->first();
                    @endphp

                    @if($userReview)
                        <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                            <p class="text-blue-700">
                                You already reviewed this book. You can update your review below.
                            </p>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2 font-medium">Rating</label>
                        <div class="flex items-center space-x-1" id="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button"
                                        data-rating="{{ $i }}"
                                        class="rating-star text-3xl {{ $userReview && $userReview->rating >= $i ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400 transition">
                                    ★
                                </button>
                            @endfor
                            <input type="hidden" name="rating" id="rating-input" value="{{ $userReview->rating ?? '' }}" required>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 mb-2 font-medium">Comment</label>
                        <textarea name="comment" rows="4"
                                  class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                  placeholder="Share your thoughts about this book...">{{ $userReview->comment ?? '' }}</textarea>
                    </div>

                    <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition font-medium">
                        {{ $userReview ? 'Update Review' : 'Submit Review' }}
                    </button>
                </form>
            </div>
        @else
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 mb-8 text-center">
                <p class="text-blue-700">
                    <a href="{{ route('login') }}" class="text-indigo-600 hover:underline font-medium">Login</a>
                    to write a review.
                </p>
            </div>
        @endauth

        {{-- Display Reviews --}}
        @if($book->reviews->count() > 0)
            <div class="space-y-6">
                @foreach($book->reviews as $review)
                    <div class="bg-white rounded-2xl shadow p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold">
                                    {{ substr($review->user->name, 0, 1) }}
                                </div>
                                <div class="ml-3">
                                    <p class="font-semibold text-gray-900">{{ $review->user->name }}</p>
                                    <div class="flex items-center mt-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="h-4 w-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                 fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                        <span class="ml-2 text-sm text-gray-500">
                                            {{ $review->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            @auth
                                @if(auth()->id() === $review->user_id || (auth()->user()->isAdmin() ?? false))
                                    <form action="{{ route('reviews.destroy', $review) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this review?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>

                        @if($review->comment)
                            <p class="text-gray-600 leading-relaxed">{{ $review->comment }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-gray-50 rounded-2xl p-8 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No reviews yet</h3>
                <p class="text-gray-600">Be the first to share your thoughts about this book!</p>
            </div>
        @endif
    </div>


    {{-- JavaScript for Star Rating --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.rating-star');
            const ratingInput = document.getElementById('rating-input');

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const rating = this.getAttribute('data-rating');
                    ratingInput.value = rating;

                    // Update star display
                    stars.forEach(s => {
                        const sRating = s.getAttribute('data-rating');
                        if (sRating <= rating) {
                            s.classList.remove('text-gray-300');
                            s.classList.add('text-yellow-400');
                        } else {
                            s.classList.remove('text-yellow-400');
                            s.classList.add('text-gray-300');
                        }
                    });
                });
            });
        });
    </script>
    @endpush
@endsection
