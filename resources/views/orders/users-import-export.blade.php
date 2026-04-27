@extends('layouts.app')

@section('title', 'User Import / Export')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- Page Header --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">User Import / Export</h2>
                    <p class="mt-1 text-sm text-gray-600">Bulk create user accounts or export user data with optional GDPR redaction.</p>
                </div>
                <a href="{{ route('admin.users.import-export') }}"
                   onclick="return false;"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 transition ease-in-out duration-150">
                    ⬇ Download User Template
                </a>
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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- IMPORT CARD --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">👥 Import Users</h3>
                    <p class="text-sm text-gray-500 mb-4">Bulk create accounts for corporate or institutional users. Existing emails are skipped automatically.</p>

                    <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        {{-- File Drop Zone --}}
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors duration-200"
                             id="user-drop-zone">
                            <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <label for="user-file" class="cursor-pointer">
                                <span class="text-blue-600 hover:text-blue-500 font-medium text-sm">Click to upload</span>
                                <span class="text-gray-500 text-sm"> or drag and drop</span>
                                <input id="user-file" name="file" type="file" accept=".xlsx,.csv" class="sr-only" onchange="updateUserFileName(this)">
                            </label>
                            <p class="text-xs text-gray-400 mt-1">XLSX or CSV up to 50MB</p>
                            <p id="user-file-name" class="text-sm text-blue-600 font-medium mt-2 hidden"></p>
                        </div>

                        @error('file')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror

                        {{-- Required columns info --}}
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs font-semibold text-gray-600 mb-1">Required columns:</p>
                            <div class="flex flex-wrap gap-1 mb-2">
                                @foreach(['Name', 'Email'] as $col)
                                    <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-xs text-gray-600 font-mono">{{ $col }}</span>
                                @endforeach
                            </div>
                            <p class="text-xs font-semibold text-gray-600 mb-1">Optional columns:</p>
                            <div class="flex flex-wrap gap-1">
                                @foreach(['Password', 'Role'] as $col)
                                    <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-xs text-gray-600 font-mono">{{ $col }}</span>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-400 mt-2">
                                💡 If no password is provided, defaults to <span class="font-mono">PageTurner@2024</span><br>
                                💡 Role must be <span class="font-mono">admin</span> or <span class="font-mono">customer</span> (defaults to customer)
                            </p>
                        </div>

                        <button type="submit"
                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Start Import
                        </button>
                    </form>
                </div>
            </div>

            {{-- EXPORT CARD --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">📤 Export Users</h3>
                    <p class="text-sm text-gray-500 mb-4">Download user data. Sensitive fields (passwords, 2FA codes, tokens) are always excluded.</p>

                    <form action="{{ route('admin.users.export') }}" method="GET" class="space-y-4">

                        {{-- GDPR Redaction --}}
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-start gap-3">
                                <input type="checkbox" name="redact_pii" id="redact_pii" value="1"
                                    class="mt-0.5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <div>
                                    <label for="redact_pii" class="text-sm font-medium text-blue-900 cursor-pointer">
                                        Enable GDPR PII Redaction
                                    </label>
                                    <p class="text-xs text-blue-700 mt-0.5">
                                        Masks names and email addresses. Example: <span class="font-mono">John Doe</span> → <span class="font-mono">J***</span>, <span class="font-mono">john@mail.com</span> → <span class="font-mono">j***@mail.com</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- What's always excluded notice --}}
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs font-semibold text-gray-600 mb-1">Always excluded from export:</p>
                            <div class="flex flex-wrap gap-1">
                                @foreach(['password', 'two_factor_code', 'two_factor_expires_at', 'remember_token'] as $col)
                                    <span class="px-2 py-0.5 bg-red-50 border border-red-200 rounded text-xs text-red-600 font-mono">{{ $col }}</span>
                                @endforeach
                            </div>
                        </div>

                        {{-- Columns included --}}
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs font-semibold text-gray-600 mb-1">Exported columns:</p>
                            <div class="flex flex-wrap gap-1">
                                @foreach(['ID', 'Name', 'Email', 'Role', 'Verified', 'Joined'] as $col)
                                    <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-xs text-gray-600 font-mono">{{ $col }}</span>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Export Users
                        </button>
                    </form>
                </div>
            </div>

        </div>

        {{-- My Orders Export (Customer-facing, shown to all auth users) --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-1">📋 Export My Order History</h3>
                <p class="text-sm text-gray-500 mb-4">Download a copy of your personal order history.</p>
                <a href="{{ route('orders.export.my') }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 transition ease-in-out duration-150">
                    ⬇ Download My Orders (XLSX)
                </a>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function updateUserFileName(input) {
        const label = document.getElementById('user-file-name');
        if (input.files && input.files[0]) {
            label.textContent = '📎 ' + input.files[0].name;
            label.classList.remove('hidden');
        }
    }

    const dropZone = document.getElementById('user-drop-zone');
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
        const fileInput = document.getElementById('user-file');
        fileInput.files = e.dataTransfer.files;
        updateUserFileName(fileInput);
    });
</script>
@endpush
