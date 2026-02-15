@extends('layouts.app')

@section('title', 'All Books - PageTurner')

@section('content')
    {{-- Search and Filter --}}
    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <form action="{{ route('books.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by title or author..."
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                >
            </div>
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
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition">
                Search
            </button>
        </form>
    </div>

    {{-- Books Grid --}}
@php
    $catId = request('category');
@endphp
@if($catId)
    @php
        $selectedCategory = $categories->firstWhere('id', $catId);
    @endphp
    @if($selectedCategory)
        <div class="text-m p-8 align-middle bg-amber-600 rounded-md">
            <div class="text-2xl">{{ $selectedCategory->name }}</div>
            {{ $selectedCategory->description }}
        </div>
    @endif
@endif
    @if($books->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($books as $book)
                <x-book-card :book="$book" />
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $books->withQueryString()->links() }}
        </div>
    @else
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-8 text-center">
            <p class="text-blue-700">No books found matching your criteria.</p>
        </div>
    @endif
@endsection
