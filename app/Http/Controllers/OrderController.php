<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Book;
use App\Models\OrderItem;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;


use App\Models\User;
use App\Mail\NewOrderNotification;
use App\Mail\OrderStatusUpdate;
use Illuminate\Support\Facades\Mail;
class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $query = Order::with(['user', 'orderItems.book']);

    // Filter by user for non-admins
    if (!auth()->user()->isAdmin()) {
        $query->where('user_id', auth()->id());
    }

    // Search by customer name (admin only)
    if (auth()->user()->isAdmin() && $request->has('customer') && !empty($request->customer)) {
        $query->whereHas('user', function($q) use ($request) {
            $q->where('name', 'LIKE', '%' . $request->customer . '%');
        });
    }

    // Filter by status
    if ($request->has('status') && $request->status !== 'all') {
        $query->where('status', $request->status);
    }

    // Sorting
    switch ($request->get('sort', 'newest')) {
        case 'oldest':
            $query->orderBy('created_at', 'asc');
            break;
        case 'price-high':
            $query->orderBy('total_amount', 'desc');
            break;
        case 'price-low':
            $query->orderBy('total_amount', 'asc');
            break;
        default: // newest
            $query->orderBy('created_at', 'desc');
    }

    $orders = $query->paginate(10)->withQueryString();

    return view('orders.index', compact('orders'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $books = Book::where('stock_quantity', '>', 0)->get();
        return view('orders.create', compact('books'));
    }




    //Search for user's current pending order
    //if such exists, add orderItem to it. Otherwise, create a new order and then add orderItem it.
    public function addToCart(Request $request,)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);
        $book = Book::findOrFail($request->book_id);
        $quantity = $request->input('quantity');
        $userId = auth()->id();
        Log::info("Adding to cart: User ID =>>> $userId, Book ID =>>> {$book->id}, Quantity =>>> $quantity");
        // Check if user has a pending order
        // dd(auth()->user() instanceof \OwenIt\Auditing\Contracts\Auditable);

        $order = Order::firstOrCreate(
            ['user_id' => $userId, 'status' => 'pending'],
            ['total_amount' => 0] // default total_amount, will be updated later
        );

        //check if no stock left first before adding to cart
        if ($book->stock_quantity < $quantity) {
            throw new \Exception("Insufficient stock for {$book->title}. Available: {$book->stock_quantity}");
        }
        // Check if the book is already in the cart
        $orderItem = OrderItem::where('order_id', $order->id)
                              ->where('book_id', $book->id)
                              ->first();

        if ($orderItem) {
            // Update quantity and unit price
            $orderItem->quantity += $quantity;
            $orderItem->unit_price = $book->price; // Update to current price
            $orderItem->save();
        } else {
            // Create new order item
            OrderItem::create([
                'order_id' => $order->id,
                'book_id' => $book->id,
                'quantity' => $quantity,
                'unit_price' => $book->price,
            ]);
        }
        // and then decrease the stock quantity of the book
        $book->decrement('stock_quantity', $quantity);


        // Update order total amount
        $order->total_amount = $order->orderItems()->sum(DB::raw('quantity * unit_price'));
        $order->save();

        return redirect()->route('orders.show', $order)
                         ->with('success', "{$book->title} added to cart!");
    }
    //When process order is called, it will check if the order is pending, if so, it will update the order status to processing and reduce the stock quantity of the ordered books. If the order is cancelled, it will restore the stock quantity of the ordered books.
    public function processOrder(Request $request)
    {
        Log::info('Processing order with request data: ', ['request' => $request->all()]);
        $order = Order::findOrFail($request->order_id);
        if ($order->status !== 'pending') {
            return back()->with('error', 'Only pending orders can be processed.');
        }

        DB::beginTransaction();

        try {
            // Update order status
            $order->update(['status' => 'processing']);

            DB::commit();
        // Send status update email to customer
        Mail::to($order->user->email)->send(new NewOrderNotification($order, 'customer'));
        //to admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new NewOrderNotification($order, 'admin'));
        }
            return redirect()->route('orders.show', $order)
                             ->with('success', 'Order processed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process order: ' . $e->getMessage());
        }
    }




    /**
     * Store a newly created resource in storage. Will be deprecated byebye!
     */
    public function store(StoreOrderRequest $request)
    {
        Log::info('Storing new order for user ID: ' . auth()->id(), ['request' => $request->all()]);
        DB::beginTransaction();
        try {
            Log::info('Storing new order for user ID: ' . auth()->id(), ['request' => $request->all()]);
            // Validate cart items
            Log::info('What is in the request?:', ['request' => $request->all()]);
            $cartItems = $request->input('order_items', []); // was cart

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
            Log::info('Failed to place order for user ID: ' . auth()->id(), ['error' => $e->getMessage()]);
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

                    // Update order status
        $oldStatus = $order->status;
        $order->status = 'processing';
        $order->save();

        // Send status update email to customer
        Mail::to($order->user->email)->send(new OrderStatusUpdate($order, $oldStatus));
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
            Log::info("Old status =>>> $oldStatus \n New Status =>>> $newStatus");
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
            Log::info("Updating the order with status $newStatus");
            $order->update(['status' => $newStatus]);

            DB::commit();

            Mail::to($order->user->email)->send(new OrderStatusUpdate($order, $oldStatus));
            return redirect()->route('orders.show', $order)
                ->with('success', "Order status updated to {$newStatus}.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', "updateStatus() caught an error: {$e->getMessage()}");
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

        if ($order->status !== 'processing') {
            return back()->with('error', 'Only processing orders can be cancelled.');
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
