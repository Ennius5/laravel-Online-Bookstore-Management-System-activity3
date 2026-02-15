@extends('layouts.app')

@auth
    @if(auth()->user()->role === 'admin')
        @section('title', 'ADMIN DASHBOARD OF CATEGORIES')
    @else
        @section('title', 'Categories')
    @endif
@endauth
@guest
@section('title', 'You should sign up if you haven\'t yet')
@endguest

@section('content')
<div class="container mx-auto px-4 py-8">
    @auth
        @if(auth()->user()->role === 'admin')
            <div class="mb-6 flex justify-between items-center">
                <h2 class="text-2xl font-bold">Manage Categories</h2>
                <button onclick="openCreateModal()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                    + Add New Category
                </button>
            </div>
        @endif
    @endauth

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($categories as $category)
            @php
                $bgColor = 'bg-green-600';
                if ($category->books_count == '0') {
                    $bgColor = 'bg-red-600';
                } elseif ($category->books_count <= '5') {
                    $bgColor = 'bg-amber-600';
                }
            @endphp

            <div class="relative group">
                <button type="button"
                        data-category-id="{{ $category->id }}"
                        data-category-name="{{ $category->name }}"
                        data-category-description="{{ $category->description }}"
                        data-original-name="{{ $category->name }}"
                        data-original-description="{{ $category->description }}"
                        data-original-count="{{ $category->books_count }}"
                        data-original-bg="{{ $bgColor }}"
                        data-toggled="false"
                        class="category-btn inline-flex w-full {{ $bgColor }} text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-all duration-300 hover:scale-110 font-medium"
                        onclick="toggleCategory(this)">

                    <span class="inline-block category-name truncate flex-1 text-left">
                        {{ $category->name }} ->
                    </span>
                    <span class="inline-block text-indigo-100 category-count ml-2">
                        {{ $category->books_count }}
                    </span>
                </button>

                @auth
                    @if(auth()->user()->role === 'admin')
                        <div class="absolute top-0 right-0 mt-2 mr-2 hidden group-hover:flex space-x-1">
                            <button onclick="openEditModal({{ $category->id }}, '{{ $category->name }}', '{{ $category->description }}')"
                                    class="bg-blue-500 text-white p-1 rounded hover:bg-blue-600 text-xs">
                                Edit
                            </button>
                            <button onclick="deleteCategory({{ $category->id }})"
                                    class="bg-red-500 text-white p-1 rounded hover:bg-red-600 text-xs">
                                Delete
                            </button>
                        </div>
                    @endif
                @endauth
            </div>
        @endforeach
    </div>

    <!-- Selected Category Info -->
    <div id="selected-category-info" class="mt-8 p-4 bg-indigo-50 rounded-lg hidden">
        <h3 class="text-lg font-semibold">Selected Category: <span id="selected-category-name" class="text-indigo-600"></span></h3>
    </div>

    <!-- Books Container -->
    <div class="mt-8">
        <h3 class="text-xl font-bold mb-4">Books in this genre:</h3>
        <div id="books-container">
            <p class="text-gray-500">Select a genre to view books</p>
        </div>
    </div>
</div>

<!-- Create/Edit Category Modal -->
<div id="categoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4" id="modalTitle">Add New Category</h3>
            <form id="categoryForm" onsubmit="saveCategory(event)">
                <input type="hidden" id="categoryId" name="categoryId">

                <div class="mb-4">
                    <label for="categoryName" class="block text-sm font-medium text-gray-700 mb-2">Category Name</label>
                    <input type="text"
                           id="categoryName"
                           name="name"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="mb-4">
                    <label for="categoryDescription" class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                    <textarea id="categoryDescription"
                              name="description"
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button"
                            onclick="closeModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Confirm Delete</h3>
            <p class="text-sm text-gray-500 mb-4">Are you sure you want to delete this category? This action cannot be undone.</p>
            <div class="flex justify-end space-x-3">
                <button type="button"
                        onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Cancel
                </button>
                <button type="button"
                        onclick="confirmDelete()"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentlySelectedButton = null;
let selectedCategoryId = null;
let categoryToDelete = null;

// Modal functions
function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Add New Category';
    document.getElementById('categoryId').value = '';
    document.getElementById('categoryForm').reset();
    document.getElementById('categoryModal').classList.remove('hidden');
}

function openEditModal(id, name, description) {
    document.getElementById('modalTitle').textContent = 'Edit Category';
    document.getElementById('categoryId').value = id;
    document.getElementById('categoryName').value = name;
    document.getElementById('categoryDescription').value = description || '';
    document.getElementById('categoryModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('categoryModal').classList.add('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    categoryToDelete = null;
}

function deleteCategory(id) {
    categoryToDelete = id;
    document.getElementById('deleteModal').classList.remove('hidden');
}

async function confirmDelete() {
    if (!categoryToDelete) return;

    try {
        const response = await fetch(`/admin/categories/${categoryToDelete}`, {  // FIXED URL
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        });

        if (response.ok) {
            window.location.reload();
        } else {
            const data = await response.json();
            alert(data.message || 'Error deleting category');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error deleting category');
    }

    closeDeleteModal();
}

async function saveCategory(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    const categoryId = document.getElementById('categoryId').value;
    const url = categoryId ? `/admin/categories/${categoryId}` : '/admin/categories';
    const method = categoryId ? 'PUT' : 'POST';

    const data = {
        name: formData.get('name'),
        description: formData.get('description')
    };

    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            credentials: 'same-origin',
            body: JSON.stringify(data)
        });

        if (response.ok) {
            window.location.reload();
        } else {
            const errorData = await response.json();
            alert(errorData.message || 'Error saving category');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error saving category');
    }
}

// Toggle functions
function toggleCategory(clickedButton) {
    const categoryId = clickedButton.getAttribute('data-category-id');
    const categoryName = clickedButton.getAttribute('data-category-name');

    if (currentlySelectedButton === clickedButton) {
        resetButton(clickedButton);
        currentlySelectedButton = null;
        selectedCategoryId = null;
        hideSelectedInfo();
        clearBooks();
        return;
    }

    if (currentlySelectedButton) {
        resetButton(currentlySelectedButton);
    }

    selectButton(clickedButton);
    currentlySelectedButton = clickedButton;
    selectedCategoryId = categoryId;

    showSelectedInfo(categoryName);
    fetchBooksByCategory(categoryId);
}

function selectButton(button) {
    const originalBg = button.getAttribute('data-original-bg');
    const categoryName = button.getAttribute('data-category-name');

    const nameSpan = button.querySelector('.category-name');
    const countSpan = button.querySelector('.category-count');

    nameSpan.textContent = 'SELECTED: ' + categoryName + ' -> ';
    countSpan.textContent = '✓';
    countSpan.className = 'inline-block text-white font-bold category-count';

    button.classList.remove(originalBg);
    button.classList.add('bg-indigo-600');

    button.setAttribute('data-toggled', 'true');
}

function resetButton(button) {
    const originalName = button.getAttribute('data-original-name');
    const originalCount = button.getAttribute('data-original-count');
    const originalBg = button.getAttribute('data-original-bg');

    const nameSpan = button.querySelector('.category-name');
    const countSpan = button.querySelector('.category-count');

    nameSpan.textContent = originalName + ' -> ';
    countSpan.textContent = originalCount;
    countSpan.className = 'inline-block text-indigo-100 category-count';

    button.className = button.className.replace(/bg-\w+-\d+/g, '');
    button.classList.add(originalBg);
    button.classList.add('text-white', 'px-6', 'py-2', 'rounded-lg', 'hover:bg-indigo-700',
                        'transition-all', 'duration-300', 'hover:scale-110', 'font-medium',
                        'w-full', 'category-btn');

    button.setAttribute('data-toggled', 'false');
}

function showSelectedInfo(categoryName) {
    const infoDiv = document.getElementById('selected-category-info');
    const nameSpan = document.getElementById('selected-category-name');

    if (infoDiv && nameSpan) {
        nameSpan.textContent = categoryName;
        infoDiv.classList.remove('hidden');
    }
}

function hideSelectedInfo() {
    const infoDiv = document.getElementById('selected-category-info');
    if (infoDiv) {
        infoDiv.classList.add('hidden');
    }
}

function fetchBooksByCategory(categoryId) {
    document.getElementById('books-container').innerHTML = '<p class="text-gray-500">Loading books...</p>';

    console.log('Fetching books for category:', categoryId);

    fetch(`/admin/categories/${categoryId}/books`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(async response => {
        console.log('Response status:', response.status);

        if (!response.ok) {
            const text = await response.text();
            console.log('Error response:', text.substring(0, 200));

            if (response.status === 401) {
                throw new Error('Unauthorized - Please make sure you are logged in as admin');
            } else {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
        }

        return response.json();
    })
    .then(data => {
        console.log('Received data:', data);
        displayBooks(data.books || data);
    })
    .catch(error => {
        console.error('Error fetching books:', error);

        let errorMessage = 'Error loading books: ' + error.message;
        if (error.message.includes('Unauthorized')) {
            errorMessage = 'Please make sure you are logged in as an admin to view books.';
        }

        document.getElementById('books-container').innerHTML = `<p class="text-red-500">${errorMessage}</p>`;
    });
}

function displayBooks(books) {
    const container = document.getElementById('books-container');

    if (!books || books.length === 0) {
        container.innerHTML = '<p class="text-gray-500">No books found in this category.</p>';
        return;
    }

    let html = '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">';

    books.forEach(book => {
        html += `
            <div class="border rounded-lg p-4 shadow hover:shadow-lg transition">
                <p class="text-gray-600 text-sm">ID: ${book.id}</p>
                <h4 class="font-bold text-lg">${book.title}</h4>
                <p class="text-gray-600">By: ${book.author}</p>
                <p class="text-sm text-gray-500">ISBN: ${book.isbn}</p>
                ${book.description ? `<p class="text-sm text-gray-600 mt-2">${book.description.substring(0, 50)}...</p>` : ''}
                <a href="/books/${book.id}" class="mt-3 block text-center bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition-colors duration-200 font-medium">
                    View Details
                </a>
            </div>
        `;
    });

    html += '</div>';
    container.innerHTML = html;
}

function clearBooks() {
    document.getElementById('books-container').innerHTML = '<p class="text-gray-500">Select a category to view books</p>';
}

function getSelectedCategoryId() {
    return selectedCategoryId;
}

function selectCategoryById(categoryId) {
    const button = document.querySelector(`.category-btn[data-category-id="${categoryId}"]`);
    if (button) {
        toggleCategory(button);
    }
}
</script>
@endpush
