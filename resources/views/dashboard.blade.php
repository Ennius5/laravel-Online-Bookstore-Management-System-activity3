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
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Edit Profile
                    </a>
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
                            <h3 class="text-lg font-semibold">Your Reviews</h3>
                        </div>

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
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-red-600">Danger Zone</h3>
                    <p class="text-sm text-gray-600 mt-1">Permanently delete your account and all associated data.</p>
                    <form method="post" action="{{ route('profile.destroy') }}" class="mt-3">
                        @csrf
                        @method('delete')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
                            Delete Account
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
