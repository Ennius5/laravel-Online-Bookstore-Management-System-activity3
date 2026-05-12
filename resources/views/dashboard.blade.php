@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Profile Summary Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Welcome, {{ $user->name }}!</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ $user->email }}</p>
                        <p class="mt-1 text-xs text-gray-500">Member since {{ $user->created_at->format('M Y') }}</p>
                    </div>
                    <div class="flex items-center gap-3">

                        @if (!auth()->user()->isAdmin())
                            <a href="{{ route('profile.edit') }}"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 transition ease-in-out duration-150">
                                Edit Profile
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Two Column Layout for Orders and Reviews -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Recent Orders -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Recent Orders</h3>
                            <a href="{{ route('orders.index') }}" class="text-sm text-blue-600 hover:text-blue-900">View all</a>
                        </div>

                        @if($recentOrders->count())
                            <div class="space-y-4">
                                @foreach($recentOrders as $order)
                                    <div class="border-b border-gray-200 pb-3 last:border-0 last:pb-0">
                                        <div class="flex justify-between">
                                            <div>
                                                <a href="{{ route('orders.show', $order) }}" class="font-medium text-gray-900 hover:text-blue-600">
                                                    Order #{{ $order->id }}
                                                </a>

                                                @php
                                                    $firstItem = $order->orderItems->first();
                                                @endphp

                                                @if($firstItem)
                                                    <p class="text-sm text-gray-600">
                                                        {{ $firstItem->book->title ?? 'Book' }}
                                                        @if($order->orderItems->count() > 1)
                                                            + {{ $order->orderItems->count() - 1 }} more
                                                        @endif
                                                    </p>
                                                @endif

                                                <p class="text-sm text-gray-600">
                                                    Total: ${{ number_format($order->total_amount ?? $order->orderItems->sum(fn($item) => $item->quantity * $item->unit_price), 2) }}
                                                </p>
                                            </div>
                                            <span class="text-xs text-gray-500">{{ $order->created_at->diffForHumans() }}</span>
                                        </div>

                                        <span class="inline-block mt-1 px-2 py-0.5 text-xs rounded-full
                                            @if($order->status === 'completed') bg-green-100 text-green-800
                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                            @elseif($order->status === 'shipped') bg-blue-100 text-blue-800
                                            @elseif($order->status === 'processing') bg-purple-100 text-purple-800
                                            @else bg-yellow-100 text-yellow-800
                                            @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No orders yet.</p>
                        @endif
                    </div>
                </div>

                <!-- Recent Reviews -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-4">
                            @if(auth()->user()->isAdmin())
                            <h3 class="text-lg font-semibold">Recent Reviews</h3>
                            @else
                            <h3 class="text-lg font-semibold">Your Reviews</h3>
                            @endif
                        </div>
                    @if(auth()->user()->isAdmin())
                        @if($adminReviews->count())
                            <div class="space-y-4">
                                @foreach($adminReviews as $review)
                                    <div class="border-b border-gray-200 pb-3 last:border-0 last:pb-0">
                                        <div class="flex justify-between">
                                            <a href="{{ route('books.show', $review->book) }}" class="font-medium text-gray-900 hover:text-blue-600">
                                                {{ $review->book->title }}
                                            </a>
                                            <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="flex items-center mt-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endfor
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">"{{ \Str::limit($review->comment, 80) }}"</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">You haven't reviewed any books yet.</p>
                        @endif
                    @else
                        @if($recentReviews->count())
                            <div class="space-y-4">
                                @foreach($recentReviews as $review)
                                    <div class="border-b border-gray-200 pb-3 last:border-0 last:pb-0">
                                        <div class="flex justify-between">
                                            <a href="{{ route('books.show', $review->book) }}" class="font-medium text-gray-900 hover:text-blue-600">
                                                {{ $review->book->title }}
                                            </a>
                                            <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="flex items-center mt-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endfor
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">"{{ \Str::limit($review->comment, 80) }}"</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">You haven't reviewed any books yet.</p>
                        @endif
                    @endif
                    </div>
                </div>
            </div>

        @if(auth()->user()->isAdmin())
    {{-- ========== ADMIN DASHBOARD WIDGETS (Section 4.6) ========== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        {{-- Widget 1: Import/Export Status --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-800 mb-3">📦 Import/Export Status</h3>
            <div class="flex justify-between mb-2">
                <div>
                    <span class="text-sm text-gray-500">Import today</span>
                    <div class="text-sm">
                        <span class="text-green-600 font-medium">✔ {{ $todayImportSuccess }}</span>
                        <span class="mx-1">/</span>
                        <span class="text-red-600 font-medium">✘ {{ $todayImportFailed }}</span>
                    </div>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Export today</span>
                    <div class="text-sm">
                        <span class="text-green-600 font-medium">✔ {{ $todayExportSuccess }}</span>
                        <span class="mx-1">/</span>
                        <span class="text-red-600 font-medium">✘ {{ $todayExportFailed }}</span>
                    </div>
                </div>
            </div>
            <div class="text-sm text-gray-700 mb-2">
                <strong>Pending queue jobs:</strong> {{ $pendingJobs }}
            </div>
            @if($importLogs && $importLogs->count())
                <div>
                    <span class="text-sm font-medium text-gray-600">Recent imports:</span>
                    <ul class="mt-1 space-y-1 text-xs text-gray-700">
                        @foreach($importLogs as $log)
                            <li class="flex justify-between items-center">
                                <span>{{ $log->filename ?? 'File' }}</span>
                                <span class="px-1.5 py-0.5 rounded text-xs font-medium
                                    {{ $log->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $log->status }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <p class="text-xs text-gray-400 mt-2">No recent imports.</p>
            @endif
        </div>

        {{-- Widget 2: Backup Status --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-800 mb-3">💾 Backup Status</h3>
            <div class="mb-2">
                <span class="text-sm">Status:</span>
                <span class="ml-1 px-2 py-0.5 rounded text-xs font-medium
                    {{ $backupStatus['healthy'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $backupStatus['healthy'] ? 'Healthy' : 'FAILING' }}
                </span>
                <span class="text-xs text-gray-500 ml-2">{{ $backupStatus['status_message'] }}</span>
            </div>
            <div class="text-sm text-gray-700 space-y-1">
                <div>📅 Last backup: <strong>{{ $backupStatus['latest_date'] }}</strong></div>
                <div>📀 Size: <strong>{{ $backupStatus['latest_size'] }}</strong></div>
                <div>💿 Disk: <strong>{{ $backupStatus['disk'] }}</strong></div>
                <div>📚 Total backups stored: <strong>{{ $backupStatus['count'] }}</strong></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
        {{-- Widget 3: Audit Log Summary --}}
        <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-800 mb-3">🔒 Audit Log Summary</h3>
            <p class="text-sm mb-2">Critical events today: <span class="font-bold">{{ $criticalAuditCount }}</span></p>
            @if(count($latestAudits))
                <ul class="space-y-2 text-xs text-gray-700">
                    @foreach($latestAudits as $audit)
                        <li class="flex justify-between items-center">
                            <span>
                                <span class="px-1.5 py-0.5 rounded bg-gray-200 text-gray-800 text-xs">{{ $audit['event'] }}</span>
                                <span class="ml-1">{{ $audit['auditable_type'] }}</span>
                                <span class="text-gray-500 ml-1">– {{ $audit['user_name'] }}</span>
                            </span>
                            <span class="text-gray-400">{{ $audit['created_at'] }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-xs text-gray-400">No recent audit entries.</p>
            @endif
        </div>

        {{-- Widget 4: API Usage Statistics --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-800 mb-3">🌐 API Usage</h3>
            <div class="text-sm text-gray-700 space-y-2">
                <div>Today requests: <strong>{{ $apiTodayRequests }}</strong></div>
                <div>Rate limited (429): <strong>{{ $apiTodayRateLimited }}</strong></div>
                @if($topEndpoints && $topEndpoints->isNotEmpty())
                    <div class="mt-3">
                        <span class="font-medium">Top endpoints</span>
                        <ul class="list-disc list-inside text-xs text-gray-600 mt-1">
                            @foreach($topEndpoints as $ep)
                                <li>{{ $ep->endpoint }} ({{ $ep->total }})</li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <p class="text-xs text-gray-400">No API data yet.</p>
                @endif
            </div>
        </div>

        {{-- Widget 5: System Health --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-800 mb-3">⚙️ System Health</h3>
            <div class="text-sm text-gray-700 space-y-2">
                <div>Database size: <strong>{{ $dbSize }}</strong></div>
                <div>Backup storage: <strong>{{ $backupDiskUsage }}</strong></div>
                <div>Queue length: <strong>{{ $queueLength }}</strong></div>
                <div>Failed jobs: <strong>{{ $failedJobsCount }}</strong></div>
            </div>
        </div>
    </div>

    {{-- Quick action links (preserved) --}}
    <div class="flex flex-wrap gap-3 mb-6">
        <a href="{{ route('admin.books.import-export') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 transition">
            📥 Import / Export Books
        </a>
        <a href="{{ route('admin.orders.export.index') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 transition">
            📦 Order Export
        </a>
        <a href="{{ route('admin.users.import-export') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 transition">
            👥 User Import/Export
        </a>
        <a href="{{ route('admin.backup.index') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 transition">
            💾 Backup Management
        </a>
        <a href="{{ route('admin.audit.index') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 transition">
            📋 Audit Log
        </a>
    </div>
@endif

        </div>
    </div>
@endsection
