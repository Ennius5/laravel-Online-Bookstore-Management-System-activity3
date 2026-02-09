@extends('layouts.app')

@section('title', 'Order #' . $order->id . ' - PageTurner')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    {{-- Order Summary --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
                    <p class="text-gray-600 mt-1">
                        Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}
                    </p>
                </div>

                {{-- Order Status Badge --}}
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'processing' => 'bg-blue-100 text-blue-800',
                        'shipped' => 'bg-indigo-100 text-indigo-800',
                        'completed' => 'bg-green-100 text-green-800',
                        'cancelled' => 'bg-red-100 text-red-800',
                    ];
                @endphp
                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>

            {{-- Customer Info --}}
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-medium text-gray-900 mb-2">Customer Information</h3>
                    <p class="text-gray-600">{{ $order->user->name }}</p>
                    <p class="text-gray-600">{{ $order->user->email }}</p>
                </div>
                <div>
                    <h3 class="font-medium text-gray-900 mb-2">Order Summary</h3>
                    <div class="space-y-1">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="text-gray-900">${{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax</span>
                            <span class="text-gray-900">${{ number_format(0, 2) }}</span> {{-- Add tax if you have it --}}
                        </div>
                        <div class="flex justify-between border-t border-gray-200 pt-2">
                            <span class="font-medium text-gray-900">Total</span>
                            <span class="font-bold text-lg text-gray-900">${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Order Items --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Order Items ({{ $order->orderItems->count() }})</h2>
        </div>

        <div class="divide-y divide-gray-200">
            @foreach($order->orderItems as $item)
            <div class="p-6">
                <div class="flex items-start">
                    {{-- Book Cover --}}
                    <div class="flex-shrink-0 w-24 h-32 bg-gray-100 rounded-lg overflow-hidden">
                        @if($item->book->cover_image)
                            <img src="{{ asset('storage/' . $item->book->cover_image) }}"
                                 alt="{{ $item->book->title }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    {{-- Book Details --}}
                    <div class="ml-6 flex-1">
                        <div class="flex justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">
                                    <a href="{{ route('books.show', $item->book) }}" class="hover:text-indigo-600">
                                        {{ $item->book->title }}
                                    </a>
                                </h3>
                                <p class="text-gray-600 mt-1">by {{ $item->book->author }}</p>
                                @if($item->book->category)
                                    <span class="inline-block mt-2 text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                        {{ $item->book->category->name }}
                                    </span>
                                @endif
                            </div>

                            <div class="text-right">
                                <p class="text-lg font-medium text-gray-900">
                                    ${{ number_format($item->unit_price, 2) }}
                                </p>
                            </div>
                        </div>

                        {{-- Quantity and Subtotal --}}
                        <div class="mt-4 flex justify-between items-center">
                            <div class="flex items-center space-x-4">
                                <span class="text-gray-600">Quantity: {{ $item->quantity }}</span>

                                {{-- Compare with current price --}}
                                @if($item->book->price != $item->unit_price)
                                    <span class="text-sm text-gray-500">
                                        Current price: ${{ number_format($item->book->price, 2) }}
                                    </span>
                                @endif
                            </div>

                            <div class="text-right">
                                <p class="text-lg font-medium text-gray-900">
                                    ${{ number_format($item->quantity * $item->unit_price, 2) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Order Actions --}}
        @if($order->isCancellable())
        <div class="p-6 border-t border-gray-200 bg-gray-50">
            <form action="{{ route('orders.cancel', $order) }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to cancel this order?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 border border-red-300 text-red-700 rounded-md hover:bg-red-50 transition">
                    Cancel Order
                </button>
            </form>
        </div>
        @endif

        {{-- Admin Actions --}}
        @auth
            @if(auth()->user()->isAdmin())
            <div class="p-6 border-t border-gray-200 bg-gray-50">
                <h3 class="font-medium text-gray-900 mb-4">Admin Actions</h3>
                <div class="flex space-x-4">
                    {{-- Update Status --}}
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="flex items-center space-x-2">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                            Update Status
                        </button>
                    </form>

                    {{-- Delete Order (Admin only) --}}
                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this order? This cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                            Delete Order
                        </button>
                    </form>
                </div>
            </div>
            @endif
        @endauth
    </div>

    {{-- Back to Orders --}}
    <div class="mt-8">
        <a href="{{ route('orders.index') }}"
           class="inline-flex items-center text-indigo-600 hover:text-indigo-800">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to All Orders
        </a>
    </div>
</div>
@endsection
