@extends('layouts.app')

@section('title', 'My Orders - PageTurner')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">My Orders</h1>
            <p class="mt-2 text-gray-600">View and manage your order history</p>
        </div>

        @auth
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.orders.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Admin Dashboard
                </a>
            @endif
        @endauth
    </div>

    {{-- Filters --}}
    <div class="mb-6 bg-white rounded-lg shadow p-4">
        <div class="flex flex-wrap items-center gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="status-filter" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="all">All Orders</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                <select id="sort-filter" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="price-high">Total: High to Low</option>
                    <option value="price-low">Total: Low to High</option>
                </select>
            </div>

            <div class="flex-1"></div>

            <div>
                <p class="text-sm text-gray-600">
                    {{ $orders->total() }} order{{ $orders->total() !== 1 ? 's' : '' }} found
                </p>
            </div>
        </div>
    </div>

    {{-- Orders List --}}
    @if($orders->isEmpty())
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No orders yet</h3>
                <p class="text-gray-600 mb-6">When you place orders, they will appear here.</p>
                <a href="{{ route('books.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Start Shopping
                </a>
            </div>
        </div>
    @else
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    {{-- Order Header --}}
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div class="mb-4 md:mb-0">
                                <div class="flex items-center">
                                    <h2 class="text-lg font-semibold text-gray-900">
                                        Order #{{ $order->id }}
                                    </h2>
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'processing' => 'bg-blue-100 text-blue-800',
                                            'shipped' => 'bg-indigo-100 text-indigo-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                    <span class="ml-3 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">
                                    Placed on {{ $order->created_at->format('M j, Y \a\t g:i A') }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    {{ $order->orderItems->count() }} item{{ $order->orderItems->count() !== 1 ? 's' : '' }}
                                </p>
                            </div>

                            <div class="flex items-center space-x-4">
                                <div class="text-right">
                                    <p class="text-lg font-bold text-gray-900">
                                        ${{ number_format($order->total_amount, 2) }}
                                    </p>
                                    @if($order->isCancellable())
                                        <span class="text-xs text-yellow-600">Cancellable</span>
                                    @endif
                                </div>

                                <a href="{{ route('orders.show', $order) }}"
                                   class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium">
                                    View Details
                                    <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Order Items Preview --}}
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-{{ min($order->orderItems->count(), 3) }} gap-4">
                            @foreach($order->orderItems->take(3) as $item)
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-16 h-20 bg-gray-100 rounded overflow-hidden">
                                        @if($item->book->cover_image)
                                            <img src="{{ asset('storage/' . $item->book->cover_image) }}"
                                                 alt="{{ $item->book->title }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-gray-900 line-clamp-1">
                                            {{ $item->book->title }}
                                        </h4>
                                        <p class="text-xs text-gray-600">Qty: {{ $item->quantity }}</p>
                                        <p class="text-sm font-medium text-gray-900">
                                            ${{ number_format($item->unit_price, 2) }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach

                            {{-- Show "more items" indicator --}}
                            @if($order->orderItems->count() > 3)
                                <div class="flex items-center justify-center">
                                    <span class="text-gray-500 text-sm">
                                        +{{ $order->orderItems->count() - 3 }} more item{{ $order->orderItems->count() - 3 !== 1 ? 's' : '' }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-4">
                                @if($order->isCancellable())
                                    <form action="{{ route('orders.cancel', $order) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-sm text-red-600 hover:text-red-800 font-medium">
                                            Cancel Order
                                        </button>
                                    </form>
                                @endif

                                @if($order->status === 'shipped')
                                    <button onclick="alert('Tracking information would appear here in a real application.')"
                                            class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                        Track Package
                                    </button>
                                @endif
                            </div>

                            @if($order->status === 'completed')
                                <button onclick="alert('Invoice download feature would be implemented here.')"
                                        class="text-sm text-gray-600 hover:text-gray-800 font-medium">
                                    Download Invoice
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @endif
    @endif
</div>

{{-- JavaScript for Filtering --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('status-filter');
    const sortFilter = document.getElementById('sort-filter');

    function applyFilters() {
        const status = statusFilter.value;
        const sort = sortFilter.value;

        // Build query parameters
        const params = new URLSearchParams();
        if (status !== 'all') params.set('status', status);
        if (sort !== 'newest') params.set('sort', sort);

        // Reload page with filters
        window.location.href = '{{ route('orders.index') }}?' + params.toString();
    }

    statusFilter.addEventListener('change', applyFilters);
    sortFilter.addEventListener('change', applyFilters);

    // Set current filter values from URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('status')) {
        statusFilter.value = urlParams.get('status');
    }
    if (urlParams.has('sort')) {
        sortFilter.value = urlParams.get('sort');
    }
});
</script>
@endpush
@endsection
