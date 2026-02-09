@props(['book'])

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300']) }}>
    {{-- Cover Image --}}
    <div class="h-48 bg-gray-100 flex items-center justify-center overflow-hidden">
        @if($book->cover_image)
            <img
                src="{{ asset('storage/' . $book->cover_image) }}"
                alt="{{ $book->title }}"
                class="h-full w-full object-cover hover:scale-105 transition-transform duration-300"
                loading="lazy"
            >
        @else
            <div class="text-gray-400">
                <svg class="h-20 w-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                    </path>
                </svg>
                <p class="text-sm mt-2">No Cover</p>
            </div>
        @endif
    </div>

    {{-- Content --}}
    <div class="p-4 flex flex-col h-full/2">
        {{-- Title --}}
        <h3 class="font-semibold text-lg text-gray-800 line-clamp-2 h-14 mb-1">
            {{ $book->title }}
        </h3>


        {{-- Author --}}
        <p class="text-gray-600 text-sm mb-2">by {{ $book->author }}</p>

        {{-- Category --}}
        @if($book->category)
            <span class="inline-block bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded-full mb-3 self-start">
                {{ $book->category->name }}
            </span>
        @endif

        {{-- Stock Status --}}
        <div class="mb-3">
            @if($book->stock_quantity > 0)
                <span class="inline-flex items-center text-sm text-green-600">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 13l4 4L19 7"></path>
                    </svg>
                    In Stock ({{ $book->stock_quantity }})
                </span>
            @else
                <span class="inline-flex items-center text-sm text-red-600">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Out of Stock
                </span>
            @endif
        </div>

        {{-- Price --}}
        <p class="text-indigo-600 font-bold text-lg mt-auto">
            ${{ number_format($book->price, 2) }}
        </p>

        {{-- Rating --}}
        <div class="flex items-center mt-2 mb-4">
            @php
                $averageRating = $book->average_rating ?? 0;
                $ratingCount = $book->reviews_count ?? $book->reviews->count() ?? 0;
                $fullStars = floor($averageRating);
                $hasHalfStar = ($averageRating - $fullStars) >= 0.5;
            @endphp

            @for($i = 1; $i <= 5; $i++)
                @if($i <= $fullStars)
                    <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @elseif($i == $fullStars + 1 && $hasHalfStar)
                    <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <defs>
                            <linearGradient id="half-gradient-{{ $book->id }}">
                                <stop offset="50%" stop-color="currentColor"/>
                                <stop offset="50%" stop-color="#D1D5DB"/>
                            </linearGradient>
                        </defs>
                        <path fill="url(#half-gradient-{{ $book->id }})"
                              d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @else
                    <svg class="h-4 w-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @endif
            @endfor

            <span class="ml-2 text-sm text-gray-500">
                {{ $averageRating > 0 ? number_format($averageRating, 1) : 'No ratings' }}
                @if($ratingCount > 0)
                    ({{ $ratingCount }})
                @endif
            </span>
        </div>

        {{-- Action Button --}}
        <a
            href="{{ route('books.show', $book) }}"
            class="mt-auto block text-center bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition-colors duration-200 font-medium"
        >
            View Details
        </a>
    </div>
</div>
