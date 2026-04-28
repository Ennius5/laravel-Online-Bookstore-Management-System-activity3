@extends('layouts.app')

@section('title', 'Import / Export Users')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- Page Header --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">User Management</h2>
                    <p class="mt-1 text-sm text-gray-600">Import and export user accounts.</p>
                </div>

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

        {{-- Two Column: Import + Export --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- IMPORT CARD (admin only) --}}
            @auth
                @if(auth()->user()->isAdmin())
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">📥 Import Users</h3>
                            <p class="text-sm text-gray-500 mb-4">Upload an XLSX or CSV file with user data. Default role: customer.</p>

                            <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
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
                                    <p class="text-xs text-gray-400 mt-1">XLSX or CSV up to 20MB</p>
                                    <p id="file-name" class="text-sm text-blue-600 font-medium mt-2 hidden"></p>
                                </div>

                                @error('file')
                                    <p class="text-xs text-red-600">{{ $message }}</p>
                                @enderror

                                {{-- Default Role --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Default Role (if not in file)</label>
                                    <select name="default_role" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="customer">Customer</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>

                                {{-- Duplicate Handling --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">If email already exists:</label>
                                    <select name="duplicate_handling" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="update">Update existing user</option>
                                        <option value="skip">Skip</option>
                                    </select>
                                </div>

                                <button type="submit"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Start Import
                                </button>
                            </form>

                            {{-- Import Tips --}}
                            <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs font-semibold text-gray-600 mb-1">Required columns in your file:</p>
                                <div class="flex flex-wrap gap-1">
                                    @foreach(['name', 'email', 'password', 'role'] as $col)
                                        <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-xs text-gray-600 font-mono">{{ $col }}</span>
                                    @endforeach
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Role values: "admin" or "customer" (default customer).</p>
                            </div>
                        </div>
                    </div>
                @endif
            @endauth

            {{-- EXPORT CARD (visible to all authenticated users) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">📤 Export Users</h3>
                    <p class="text-sm text-gray-500 mb-4">Export user data with optional PII redaction for GDPR compliance.</p>

                    <form action="{{ route('admin.users.export') }}" method="GET" class="space-y-4">
                        {{-- Format --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Export Format</label>
                            <select name="format" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="xlsx">📊 XLSX</option>
                                <option value="csv">📄 CSV</option>
                            </select>
                        </div>

                        {{-- Role Filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role (optional)</label>
                            <select name="role" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Roles</option>
                                <option value="admin">Admin</option>
                                <option value="customer">Customer</option>
                            </select>
                        </div>

                        {{-- GDPR: Redact PII --}}
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="redact_pii" id="redact_pii" value="1"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="redact_pii" class="text-sm text-gray-700">
                                Redact personal info (GDPR compliant) — emails will be anonymized, names removed.
                            </label>
                        </div>

                        <button type="submit"
                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Export Users
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Recent Export Logs (optional, admin only) --}}
        @auth
            @if(auth()->user()->isAdmin() && isset($exportLogs))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent User Export Jobs</h3>
                        @if($exportLogs->count())
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Format</th>
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
                                No user exports yet.
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
