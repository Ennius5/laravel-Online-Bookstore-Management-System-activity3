@extends('layouts.app')

@section('title', 'Order Export')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- Page Header --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Order Export</h2>
                    <p class="mt-1 text-sm text-gray-600">Export order data and generate financial reports.</p>
                </div>
                <a href="{{ route('admin.orders.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 transition ease-in-out duration-150">
                    ← Back to Orders
                </a>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- Two Column: Order Export + Financial Report --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- ADMIN ORDER EXPORT --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">📦 Export Orders</h3>
                    <p class="text-sm text-gray-500 mb-4">Filter and download order records.</p>

                    <form action="{{ route('admin.orders.export.download') }}" method="GET" class="space-y-4">

                        {{-- Status Filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Order Status</label>
                            <select name="status" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="shipped">Shipped</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>

                        {{-- Date Range --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                                <input type="date" name="date_from"
                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                                <input type="date" name="date_to"
                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        {{-- Customer Filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Customer (optional)</label>
                            <select name="user_id" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Customers</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Format --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Format</label>
                            <div class="flex gap-3">
                                @foreach(['xlsx' => '📊 XLSX', 'csv' => '📄 CSV'] as $value => $label)
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="format" value="{{ $value }}" class="sr-only peer" {{ $value === 'xlsx' ? 'checked' : '' }}>
                                        <div class="text-center px-2 py-2 border-2 border-gray-200 rounded-lg text-sm text-gray-600 peer-checked:border-blue-500 peer-checked:text-blue-600 peer-checked:bg-blue-50 hover:border-gray-300 transition-all duration-150">
                                            {{ $label }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Export Orders
                        </button>
                    </form>
                </div>
            </div>

            {{-- FINANCIAL REPORT --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">💰 Financial Report</h3>
                    <p class="text-sm text-gray-500 mb-4">Export revenue summaries with tax calculations (12% VAT). Cancelled orders are excluded.</p>

                    <form action="{{ route('admin.orders.export.financial') }}" method="GET" class="space-y-4">

                        {{-- Date Range --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                                <input type="date" name="date_from"
                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                                <input type="date" name="date_to"
                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        {{-- Summary Info Box --}}
                        <div class="p-3 bg-gray-50 rounded-lg space-y-1">
                            <p class="text-xs font-semibold text-gray-600">Report includes:</p>
                            <ul class="text-xs text-gray-500 space-y-0.5 list-disc list-inside">
                                <li>Order subtotals</li>
                                <li>12% VAT per order</li>
                                <li>Grand totals per order</li>
                                <li>Excludes cancelled orders</li>
                            </ul>
                        </div>

                        <button type="submit"
                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Download Financial Report
                        </button>
                    </form>

                    {{-- Divider --}}
                    <div class="my-5 border-t border-gray-200"></div>

                    {{-- My Orders Export (for non-admins this would be the only section visible) --}}
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">🧾 Customer Invoice</h3>
                    <p class="text-sm text-gray-500 mb-3">Generate a PDF invoice for a specific order.</p>

                    <form action="" method="GET" id="invoice-form" class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Order ID</label>
                            <input type="number" id="invoice-order-id" min="1" placeholder="e.g. 42"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <button type="button" onclick="goToInvoice()"
                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-500 transition ease-in-out duration-150">
                            Download Invoice PDF
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
function goToInvoice() {
    const orderId = document.getElementById('invoice-order-id').value;
    if (!orderId) {
        alert('Please enter an Order ID.');
        return;
    }
    window.location.href = `/orders/${orderId}/invoice`;
}
</script>
@endpush
