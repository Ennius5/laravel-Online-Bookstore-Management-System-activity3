@extends('layouts.app')

@section('title', 'PageTurner - Online Bookstore')

@section('content')
    {{-- Hero Section --}}
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-2xl p-8 mb-12">
        <div class="max-w-3xl">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Welcome to PageTurner</h1>
            <p class="text-xl text-indigo-100 mb-8 leading-relaxed">
                Discover your next favorite book from our extensive collection.
            </p>
            <a
                href="{{ route('books.index') }}"
                class="inline-flex items-center justify-center bg-white text-indigo-700 px-8 py-4 rounded-xl font-semibold hover:bg-indigo-50 transition-all duration-300 shadow-lg hover:shadow-xl"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Browse Books
            </a>
        </div>
    </div>

    {{-- Categories Section --}}
    <section class="mb-12">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6">Browse by Category</h2>

        @if($categories->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($categories as $category)
                    <a
                        href="{{ route('categories.show', $category) }}"
                        class="group bg-white p-4 rounded-lg shadow hover:shadow-md transition text-center border border-gray-100 hover:border-indigo-200"
                    >
                        <h3 class="font-semibold text-gray-800 group-hover:text-indigo-700">{{ $category->name }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $category->books_count }} books</p>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-8">No categories available yet.</p>
        @endif
    </section>

    {{-- Featured Books Section --}}
    <section>
        <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6">Featured Books</h2>

        @if($featuredBooks->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($featuredBooks as $book)
                    <x-book-card :book="$book" />
                @endforeach
            </div>
        @else
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
                <svg class="w-12 h-12 text-blue-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-blue-700">No books available at the moment. Check back soon!</p>
            </div>
        @endif
    </section>
@endsection
