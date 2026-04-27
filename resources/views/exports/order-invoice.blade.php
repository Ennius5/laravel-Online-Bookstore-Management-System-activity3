<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 13px; color: #333; }
        h1 { color: #c2410c; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th { background: #f3f4f6; text-align: left; padding: 8px; border: 1px solid #e5e7eb; }
        td { padding: 8px; border: 1px solid #e5e7eb; }
        .total { text-align: right; margin-top: 16px; font-weight: bold; }
        .footer { margin-top: 40px; font-size: 11px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>
    <h1>PageTurner Bookstore</h1>
    <p><strong>Invoice for Order #{{ $order->id }}</strong></p>
    <p>Date: {{ $order->created_at->format('F j, Y') }}</p>
    <p>Customer: {{ $order->user->name }} ({{ $order->user->email }})</p>
    <p>Status: {{ ucfirst($order->status) }}</p>

    <table>
        <thead>
            <tr>
                <th>Book</th>
                <th>ISBN</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
            <tr>
                <td>{{ $item->book->title ?? 'N/A' }}</td>
                <td>{{ $item->book->isbn ?? 'N/A' }}</td>
                <td>{{ $item->quantity }}</td>
                <td>${{ number_format($item->unit_price, 2) }}</td>
                <td>${{ number_format($item->quantity * $item->unit_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <p>Total Amount: ${{ number_format($order->total_amount, 2) }}</p>
        <p>Tax (12%): ${{ number_format($order->total_amount * 0.12, 2) }}</p>
        <p>Grand Total: ${{ number_format($order->total_amount * 1.12, 2) }}</p>
    </div>

    <div class="footer">
        Thank you for shopping at PageTurner! This is a system-generated invoice.
    </div>
</body>
</html>
