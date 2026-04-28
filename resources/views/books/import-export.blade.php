@extends('layouts.app')

@section('title', 'Import / Export Books')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- Page Header --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Book Catalogue Management</h2>
                    <p class="mt-1 text-sm text-gray-600">Import and export your catalogue in multiple formats.</p>
                </div>
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.books.template') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 transition ease-in-out duration-150">
                            ⬇ Download Import Template
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
                ❌ {{ session('error') }}
            </div>
        @endif

        {{-- Two Columns: Import + Export --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- IMPORT CARD (admin only) --}}
            @auth
                @if(auth()->user()->isAdmin())
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">📥 Import Books</h3>
                            <p class="text-sm text-gray-500 mb-4">Upload an XLSX or CSV file. Large files are processed in the background.</p>

                            <form action="{{ route('admin.books.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf

                                {{-- File Drop Zone --}}
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors duration-200"
                                     id="drop-zone">
                                    <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <label for="file" class="cursor-pointer">
                                        <span class="text-blue-600 hover:text-blue-500 font-medium text-sm">Click to upload</span>
                                        <span class="text-gray-500 text-sm"> or drag and drop</span>
                                        <input id="file" name="file" type="file" accept=".xlsx,.csv" class="sr-only" onchange="updateFileName(this)">
                                    </label>
                                    <p class="text-xs text-gray-400 mt-1">XLSX or CSV up to 50MB</p>
                                    <p id="file-name" class="text-sm text-blue-600 font-medium mt-2 hidden"></p>
                                </div>

                                @error('file')
                                    <p class="text-xs text-red-600">{{ $message }}</p>
                                @enderror

                                {{-- Duplicate Handling --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">If a book already exists (same ISBN):</label>
                                    <select name="duplicate_handling" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="update">Update existing record</option>
                                        <option value="skip">Skip duplicate</option>
                                    </select>
                                </div>

                                <button type="submit"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Start Import
                                </button>
                            </form>

                            <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs font-semibold text-gray-600 mb-1">Required columns in your file:</p>
                                <div class="flex flex-wrap gap-1">
                                    @foreach(['ISBN', 'Title', 'Author', 'Price', 'Stock', 'Category', 'Description'] as $col)
                                        <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-xs text-gray-600 font-mono">{{ $col }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endauth

            {{-- EXPORT CARD (visible to all authenticated users) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">📤 Export Books</h3>
                    <p class="text-sm text-gray-500 mb-4">Filter your catalogue and download in your preferred format.</p>

                    @php $exportRoute = auth()->user()->isAdmin() ? 'admin.books.export' : 'books.export'; @endphp
                    <form action="{{ route($exportRoute) }}" method="GET" class="space-y-4">
                        {{-- Format --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Export Format</label>
                            <div class="flex gap-3">
                                @foreach(['xlsx' => '📊 XLSX'] as $value => $label)
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="format" value="{{ $value }}" class="sr-only peer" {{ $value === 'xlsx' ? 'checked' : '' }}>
                                        <div class="text-center px-2 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-600 peer-checked:border-blue-500 peer-checked:text-blue-600 peer-checked:bg-blue-50 hover:border-gray-300 transition-all duration-150">
                                            {{ $label }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Category Filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category (optional)</label>
                            <select name="category_id" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Categories</option>
                                @foreach(\App\Models\Category::orderBy('name')->get() as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Price Range --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Min Price</label>
                                <input type="number" name="min_price" min="0" step="0.01" placeholder="0.00"
                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Max Price</label>
                                <input type="number" name="max_price" min="0" step="0.01" placeholder="9999.99"
                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        {{-- Stock Filter --}}
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="in_stock" id="in_stock" value="1"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="in_stock" class="text-sm text-gray-700">Only export in-stock books</label>
                        </div>

                        {{-- Date Range --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date Added From</label>
                                <input type="date" name="date_from"
                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date Added To</label>
                                <input type="date" name="date_to"
                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        {{-- Custom Column Selection --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Columns to Export</label>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach(['isbn' => 'ISBN', 'title' => 'Title', 'author' => 'Author', 'price' => 'Price', 'stock_quantity' => 'Stock', 'category' => 'Category', 'description' => 'Description', 'created_at' => 'Date Added'] as $value => $label)
                                    <label class="flex items-center gap-2 text-sm text-gray-700">
                                        <input type="checkbox" name="columns[]" value="{{ $value }}" checked
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        {{ $label }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Export Now
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Import Logs (admin only) --}}
        @auth
            @if(auth()->user()->isAdmin())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Import Jobs</h3>
                        @if($importLogs->count())
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Filename</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rows</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($importLogs as $log)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-3 text-xs">{{ $log->filename }}</td>
                                                <td class="px-4 py-3 text-xs">
                                                    @if($log->total_rows)
                                                        {{ $log->processed_rows }}/{{ $log->total_rows }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3">
                                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                                        @if($log->status === 'completed') bg-green-100 text-green-800
                                                        @elseif($log->status === 'failed') bg-red-100 text-red-800
                                                        @else bg-yellow-100 text-yellow-800
                                                        @endif">
                                                        {{ ucfirst($log->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-gray-500 text-xs">{{ $log->created_at->diffForHumans() }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-4 bg-gray-50 rounded-lg text-center text-gray-500 text-sm">
                                No imports yet.
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Export Logs (admin only) --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Export Jobs</h3>
                        @if($exportLogs->count())
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Format</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Filters Applied</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($exportLogs as $log)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-3">
                                                    <span class="px-2 py-0.5 bg-gray-100 rounded text-xs font-mono uppercase">{{ $log->format }}</span>
                                                </td>
                                                <td class="px-4 py-3 text-gray-500 text-xs">
                                                    @if($log->filters && count(array_filter((array) $log->filters)))
                                                        {{ collect($log->filters)->filter()->keys()->map(fn($k) => ucfirst(str_replace('_', ' ', $k)))->join(', ') }}
                                                    @else
                                                        No filters
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3">
                                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                                        @if($log->status === 'completed') bg-green-100 text-green-800
                                                        @elseif($log->status === 'failed') bg-red-100 text-red-800
                                                        @else bg-yellow-100 text-yellow-800
                                                        @endif">
                                                        {{ ucfirst($log->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-gray-500 text-xs">{{ $log->created_at->diffForHumans() }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-4 bg-gray-50 rounded-lg text-center text-gray-500 text-sm">
                                No exports yet.
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @endauth
    </div>
</div>
@endsection

@push('scripts')
<script>
    function updateFileName(input) {
        const label = document.getElementById('file-name');
        if (input.files && input.files[0]) {
            label.textContent = '📎 ' + input.files[0].name;
            label.classList.remove('hidden');
        }
    }

    const dropZone = document.getElementById('drop-zone');
    if (dropZone) {
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-blue-400', 'bg-blue-50');
        });
        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-blue-400', 'bg-blue-50');
        });
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-blue-400', 'bg-blue-50');
            const fileInput = document.getElementById('file');
            fileInput.files = e.dataTransfer.files;
            updateFileName(fileInput);
        });
    }
</script>
@endpush
