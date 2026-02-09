<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Book;
use App\Models\OrderItem;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.book'])
            ->latest();

        // Filter by status if provided
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by user if admin, otherwise show only user's orders
        if (!auth()->user()->isAdmin()) {//false positive from intelliphense,
            $query->where('user_id', auth()->id());//false positive from intelliphense,
        } elseif ($request->has('user_id') && $request->user_id != '') {
            $query->where('user_id', $request->user_id);
        }

        // Search by order ID or user name/email
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Date range filtering
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $orders = $query->paginate(15);
        $statuses = ['pending', 'processing', 'completed', 'cancelled', 'shipped'];

        return view('orders.index', compact('orders', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $books = Book::where('stock_quantity', '>', 0)->get();
        return view('orders.create', compact('books'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        DB::beginTransaction();

        try {
            // Validate cart items
            $cartItems = $request->input('cart', []);

            if (empty($cartItems)) {
                return back()->with('error', 'Your cart is empty.');
            }

            // Calculate total and validate stock
            $totalAmount = 0;
            $orderItems = [];

            foreach ($cartItems as $item) {
                $book = Book::findOrFail($item['book_id']);

                if ($book->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$book->title}. Available: {$book->stock_quantity}");
                }

                $unitPrice = $book->price;
                $subtotal = $item['quantity'] * $unitPrice;
                $totalAmount += $subtotal;

                $orderItems[] = [
                    'book_id' => $book->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                ];

                // Reduce stock quantity
                $book->decrement('stock_quantity', $item['quantity']);
            }

            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'total_amount' => $totalAmount,
                'status' => 'pending',
            ]);

            // Create order items
            foreach ($orderItems as $orderItem) {
                $orderItem['order_id'] = $order->id;
                OrderItem::create($orderItem);
            }

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        // Check authorization
        if (auth()->user() && !auth()->user()->isAdmin() && $order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $order->load(['user', 'orderItems.book']);

        // Calculate totals for display
        $order->subtotal = $order->orderItems->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });

        // If you have tax/shipping
        // $order->tax = $order->subtotal * 0.08; // Example 8% tax
        // $order->shipping = 5.99; // Example shipping
        // $order->total = $order->subtotal + $order->tax + $order->shipping;

        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        // Only allow editing pending orders
        if ($order->status !== 'pending') {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Only pending orders can be edited.');
        }

        // Check authorization
        if (auth()->user() && !auth()->user()->isAdmin() && $order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $order->load('orderItems.book');
        $books = Book::where('stock_quantity', '>', 0)->get();

        return view('orders.edit', compact('order', 'books'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        // Check authorization
        if (auth()->user() && !auth()->user()->isAdmin() && $order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        DB::beginTransaction();

        try {
            // Handle status updates
            if ($request->has('status')) {
                $newStatus = $request->status;

                // If cancelling order, restore stock
                if ($order->status !== 'cancelled' && $newStatus === 'cancelled') {
                    foreach ($order->orderItems as $item) {
                        $item->book->increment('stock_quantity', $item->quantity);
                    }
                }

                // If uncancelling order, deduct stock
                if ($order->status === 'cancelled' && $newStatus !== 'cancelled') {
                    foreach ($order->orderItems as $item) {
                        if ($item->book->stock_quantity < $item->quantity) {
                            throw new \Exception("Insufficient stock for {$item->book->title}. Available: {$item->book->stock_quantity}");
                        }
                        $item->book->decrement('stock_quantity', $item->quantity);
                    }
                }

                $order->update(['status' => $newStatus]);
            }

            // Handle order items update (only for pending orders)
            if ($order->status === 'pending' && $request->has('cart')) {
                $cartItems = $request->input('cart', []);

                // Remove existing items and restore stock
                foreach ($order->orderItems as $item) {
                    $item->book->increment('stock_quantity', $item->quantity);
                    $item->delete();
                }

                // Add new items
                $totalAmount = 0;
                foreach ($cartItems as $item) {
                    $book = Book::findOrFail($item['book_id']);

                    if ($book->stock_quantity < $item['quantity']) {
                        throw new \Exception("Insufficient stock for {$book->title}. Available: {$book->stock_quantity}");
                    }

                    $unitPrice = $book->price;
                    $subtotal = $item['quantity'] * $unitPrice;
                    $totalAmount += $subtotal;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'book_id' => $book->id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $unitPrice,
                    ]);

                    $book->decrement('stock_quantity', $item['quantity']);
                }

                $order->update(['total_amount' => $totalAmount]);
            }

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Order updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        // Only admins can delete orders
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        DB::beginTransaction();

        try {
            // Restore stock if order wasn't cancelled
            if ($order->status !== 'cancelled') {
                foreach ($order->orderItems as $item) {
                    $item->book->increment('stock_quantity', $item->quantity);
                }
            }

            // Delete order items first
            $order->orderItems()->delete();

            // Delete the order
            $order->delete();

            DB::commit();

            return redirect()->route('orders.index')
                ->with('success', 'Order deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete order: ' . $e->getMessage());
        }
    }

    /**
     * User order history.
     */
    public function history()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('orderItems.book')
            ->latest()
            ->paginate(10);

        return view('orders.history', compact('orders'));
    }

    /**
     * Update order status only.
     */
    public function updateStatus(Request $request, Order $order)
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled'
        ]);

        DB::beginTransaction();

        try {
            $oldStatus = $order->status;
            $newStatus = $request->status;

            // Handle stock adjustments for cancellations/uncancellations
            if ($oldStatus !== 'cancelled' && $newStatus === 'cancelled') {
                // Restore stock
                foreach ($order->orderItems as $item) {
                    $item->book->increment('stock_quantity', $item->quantity);
                }
            } elseif ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                // Deduct stock
                foreach ($order->orderItems as $item) {
                    if ($item->book->stock_quantity < $item->quantity) {
                        throw new \Exception("Insufficient stock for {$item->book->title}. Available: {$item->book->stock_quantity}");
                    }
                    $item->book->decrement('stock_quantity', $item->quantity);
                }
            }

            $order->update(['status' => $newStatus]);

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', "Order status updated to {$newStatus}.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Generate invoice PDF.
     */
    public function invoice(Order $order)
    {
        // Check authorization
        if (auth()->user() && !auth()->user()->isAdmin() && $order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $order->load(['user', 'orderItems.book']);

        // Use a PDF library like dompdf, barryvdh/laravel-dompdf, or mpdf
        // return PDF::loadView('orders.invoice', compact('order'))->download("invoice-{$order->id}.pdf");

        // For now, return a view
        return view('orders.invoice', compact('order'));
    }

    /**
     * Cancel order (user action).
     */
    public function cancel(Order $order)
    {
        // Only owner can cancel pending orders
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'Only pending orders can be cancelled.');
        }

        DB::beginTransaction();

        try {
            // Restore stock
            foreach ($order->orderItems as $item) {
                $item->book->increment('stock_quantity', $item->quantity);
            }

            $order->update(['status' => 'cancelled']);

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Order cancelled successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to cancel order: ' . $e->getMessage());
        }
    }
}
