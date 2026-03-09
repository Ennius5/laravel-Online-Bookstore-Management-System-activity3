@extends('layouts.app')

@section('title', 'All Books - PageTurner')

@section('content')
    {{-- Search and Filter --}}
    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <form action="{{ route('books.index') }}" method="GET" class="flex flex-wrap gap-4">
            {{-- Search Input with Type Selection --}}
            <div class="flex-1 min-w-75">
                <div class="flex rounded-md shadow-sm">
                    {{-- Search Type Dropdown --}}
                    <select
                        name="search_type"
                        class="rounded-l-md border-gray-300 bg-gray-50 text-gray-500 sm:text-sm focus:ring-indigo-500 focus:border-indigo-500"
                    >
                        <option value="title" {{ request('search_type') == 'title' ? 'selected' : '' }}>Title</option>
                        <option value="author" {{ request('search_type') == 'author' ? 'selected' : '' }}>Author</option>
                        <option value="both" {{ request('search_type') == 'both' || !request('search_type') ? 'selected' : '' }}>Title & Author</option>
                    </select>

                    {{-- Search Input --}}
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search books..."
                        class="flex-1 border-gray-300 rounded-r-md focus:ring-indigo-500 focus:border-indigo-500"
                    >
                </div>
            </div>

            {{-- Category Filter --}}
            <div class="w-48">
                <select
                    name="category"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                >
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option
                            value="{{ $category->id }}"
                            {{ request('category') == $category->id ? 'selected' : '' }}
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Sort Options --}}
            <div class="w-48">
                <select
                    name="sort"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                >
                    <option value="">Default Sorting</option>
                    <optgroup label="Price">
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    </optgroup>
                    <optgroup label="Rating">
                        <option value="rating_low" {{ request('sort') == 'rating_low' ? 'selected' : '' }}>Rating: Low to High</option>
                        <option value="rating_high" {{ request('sort') == 'rating_high' ? 'selected' : '' }}>Rating: High to Low</option>
                    </optgroup>
                    <optgroup label="Other">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest Arrivals</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Title A-Z</option>
                        <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Title Z-A</option>
                    </optgroup>
                </select>
            </div>

            {{-- Items Per Page --}}
            <div class="w-24">
                <select
                    name="per_page"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                >
                    <option value="12" {{ request('per_page', 12) == 12 ? 'selected' : '' }}>12</option>
                    <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24</option>
                    <option value="36" {{ request('per_page') == 36 ? 'selected' : '' }}>36</option>
                    <option value="48" {{ request('per_page') == 48 ? 'selected' : '' }}>48</option>
                </select>
            </div>

            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Search
            </button>

            {{-- Reset Filters Button --}}
            @if(request()->anyFilled(['search', 'category', 'sort', 'per_page']))
                <a href="{{ route('books.index') }}"
                   class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Clear
                </a>
            @endif
        </form>

        {{-- Active Filters Display --}}
        @if(request()->anyFilled(['search', 'category', 'sort']) || request('search_type') != 'both')
            <div class="mt-3 flex flex-wrap items-center gap-2">
                <span class="text-sm text-gray-600">Active filters:</span>

                @if(request('search'))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        {{ ucfirst(request('search_type', 'both')) }}: "{{ request('search') }}"
                        <a href="{{ route('books.index', array_merge(request()->except('search', 'search_type'), ['search' => null, 'search_type' => 'both'])) }}"
                           class="ml-1 text-indigo-600 hover:text-indigo-800">×</a>
                    </span>
                @endif

                @if(request('category'))
                    @php
                        $selectedCategory = $categories->firstWhere('id', request('category'));
                    @endphp
                    @if($selectedCategory)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            Category: {{ $selectedCategory->name }}
                            <a href="{{ route('books.index', request()->except('category')) }}"
                               class="ml-1 text-indigo-600 hover:text-indigo-800">×</a>
                        </span>
                    @endif
                @endif

                @if(request('sort'))
                    @php
                        $sortLabels = [
                            'price_low' => 'Price: Low to High',
                            'price_high' => 'Price: High to Low',
                            'rating_low' => 'Rating: Low to High',
                            'rating_high' => 'Rating: High to Low',
                            'newest' => 'Newest Arrivals',
                            'oldest' => 'Oldest First',
                            'title_asc' => 'Title A-Z',
                            'title_desc' => 'Title Z-A',
                        ];
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        Sort: {{ $sortLabels[request('sort')] ?? ucfirst(str_replace('_', ' ', request('sort'))) }}
                        <a href="{{ route('books.index', request()->except('sort')) }}"
                           class="ml-1 text-indigo-600 hover:text-indigo-800">×</a>
                    </span>
                @endif

                @if(request('per_page') && request('per_page') != 12)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        Per page: {{ request('per_page') }}
                        <a href="{{ route('books.index', array_merge(request()->except('per_page'), ['per_page' => 12])) }}"
                           class="ml-1 text-indigo-600 hover:text-indigo-800">×</a>
                    </span>
                @endif

                <a href="{{ route('books.index') }}" class="text-sm text-red-600 hover:text-red-800 ml-2">
                    Clear all
                </a>
            </div>
        @endif
    </div>

    {{-- Category Header --}}
    @php
        $catId = request('category');
    @endphp
    @if($catId)
        @php
            $selectedCategory = $categories->firstWhere('id', $catId);
        @endphp
        @if($selectedCategory)
            <div class="mb-6 p-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg shadow-lg text-white">
                <h1 class="text-3xl font-bold mb-2">{{ $selectedCategory->name }}</h1>
                @if($selectedCategory->description)
                    <p class="text-indigo-100">{{ $selectedCategory->description }}</p>
                @endif
                <div class="mt-4 text-sm text-indigo-100">
                    Showing {{ $books->firstItem() ?? 0 }} - {{ $books->lastItem() ?? 0 }} of {{ $books->total() }} books
                </div>
            </div>
        @endif
    @else
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-amber-500">All Books</h1>
            <p class="text-gray-600 mt-2">Discover our collection of {{ $books->total() }} books</p>
        </div>
    @endif

    {{-- Books Grid --}}
    @if($books->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($books as $book)
                <x-book-card :book="$book" />
            @endforeach
        </div>

        {{-- Pagination with per page selector --}}
        <div class="mt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="text-sm text-gray-600">
                Showing {{ $books->firstItem() ?? 0 }} to {{ $books->lastItem() ?? 0 }} of {{ $books->total() }} books
            </div>

            <div class="flex items-center gap-4">
                {{-- Per page selector --}}
                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-600">Show:</label>
                    <select
                        onchange="window.location.href = '{{ route('books.index') }}?' + new URLSearchParams({...Object.fromEntries(new URLSearchParams(window.location.search)), per_page: this.value}).toString()"
                        class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                    >
                        <option value="12" {{ request('per_page', 12) == 12 ? 'selected' : '' }}>12</option>
                        <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24</option>
                        <option value="36" {{ request('per_page') == 36 ? 'selected' : '' }}>36</option>
                        <option value="48" {{ request('per_page') == 48 ? 'selected' : '' }}>48</option>
                    </select>
                    <span class="text-sm text-gray-600">per page</span>
                </div>

                {{ $books->withQueryString()->links() }}
            </div>
        </div>
    @else
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-12 text-center">
            <svg class="w-16 h-16 text-blue-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <h2 class="text-2xl font-bold text-amber-500 mb-2">No books found</h2>
            <p class="text-gray-600 mb-6">We couldn't find any books matching your search criteria.</p>
            <a href="{{ route('books.index') }}"
               class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Clear all filters
            </a>
        </div>
    @endif
@endsection
