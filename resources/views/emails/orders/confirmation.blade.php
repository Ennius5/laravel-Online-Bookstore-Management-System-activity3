<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - PageTurner</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.5;
            color: #333;
            background-color: #f4f4f7;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }
        .header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            padding: 30px 20px;
            text-align: center;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: white;
            margin-bottom: 10px;
        }
        .header-title {
            color: rgba(255, 255, 255, 0.9);
            font-size: 18px;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 15px;
        }
        .message {
            color: #6b7280;
            margin-bottom: 25px;
            font-size: 16px;
        }
        .order-info {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .order-info-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .order-info-item:last-child {
            border-bottom: none;
        }
        .order-info-label {
            color: #6b7280;
            font-weight: 500;
        }
        .order-info-value {
            color: #1f2937;
            font-weight: 600;
        }
        .status-badge {
            display: inline-block;
            background-color: #fef3c7;
            color: #92400e;
            padding: 6px 12px;
            border-radius: 9999px;
            font-size: 14px;
            font-weight: 500;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
        }
        .items-table th {
            background-color: #f9fafb;
            padding: 12px;
            text-align: left;
            font-size: 14px;
            font-weight: 600;
            color: #4b5563;
            border-bottom: 2px solid #e5e7eb;
        }
        .items-table td {
            padding: 15px 12px;
            border-bottom: 1px solid #e5e7eb;
            color: #4b5563;
        }
        .items-table tr:last-child td {
            border-bottom: none;
        }
        .total-section {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 16px;
        }
        .grand-total {
            font-size: 20px;
            font-weight: bold;
            color: #4f46e5;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #e5e7eb;
        }
        .button {
            display: inline-block;
            background-color: #4f46e5;
            color: white;
            text-decoration: none;
            padding: 14px 30px;
            border-radius: 8px;
            font-weight: 600;
            margin: 25px 0 15px;
            transition: background-color 0.2s;
        }
        .button:hover {
            background-color: #4338ca;
        }
        .footer {
            padding: 30px;
            text-align: center;
            background-color: #f9fafb;
            color: #9ca3af;
            font-size: 14px;
            border-top: 1px solid #e5e7eb;
        }
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #9ca3af;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">📚 PageTurner</div>
            <div class="header-title">Your Digital Bookstore</div>
        </div>

        <div class="content">
            <div class="greeting">
                Thank you for your order, {{ $order->user->name }}!
            </div>

            <div class="message">
                We're pleased to confirm that your order has been received and is being processed. Below are the details of your purchase.
            </div>

            <div class="order-info">
                <div class="order-info-item">
                    <span class="order-info-label">Order Number:</span>
                    <span class="order-info-value">#{{ $order->id }}</span>
                </div>
                <div class="order-info-item">
                    <span class="order-info-label">Order Date:</span>
                    <span class="order-info-value">{{ $order->created_at->format('F j, Y \a\t g:i A') }}</span>
                </div>
                <div class="order-info-item">
                    <span class="order-info-label">Status:</span>
                    <span class="order-info-value">
                        <span class="status-badge">{{ ucfirst($order->status) }}</span>
                    </span>
                </div>
                <div class="order-info-item">
                    <span class="order-info-label">Payment Method:</span>
                    <span class="order-info-value">{{ $order->payment_method ?? 'Credit Card' }}</span>
                </div>
            </div>

            <h3 style="font-size: 18px; color: #1f2937; margin-bottom: 15px;">Order Items</h3>

            <table class="items-table">
                <thead>
                    <tr>
                        <th>Book</th>
                        <th>Author</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->book->title }}</strong>
                        </td>
                        <td>{{ $item->book->author }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->unit_price, 2) }}</td>
                        <td>${{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total-section">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>${{ number_format($order->subtotal ?? $order->total_amount, 2) }}</span>
                </div>
                <div class="total-row">
                    <span>Shipping:</span>
                    <span>Free</span>
                </div>
                <div class="total-row">
                    <span>Tax:</span>
                    <span>${{ number_format($order->tax ?? 0, 2) }}</span>
                </div>
                <div class="grand-total">
                    <span>Total:</span>
                    <span>${{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('orders.show', $order) }}" class="button">
                    View Order Details
                </a>
            </div>

            <div style="background-color: #f0f9ff; border-left: 4px solid #4f46e5; padding: 15px; margin-top: 25px; border-radius: 4px;">
                <p style="color: #1e40af; margin: 0;">
                    <strong>📖 What's Next?</strong><br>
                    You'll receive another email when your order ships. If you have any questions, please contact our support team.
                </p>
            </div>
        </div>

        <div class="footer">
            <div class="social-links">
                <a href="#">Facebook</a> •
                <a href="#">Twitter</a> •
                <a href="#">Instagram</a>
            </div>
            <p>&copy; {{ date('Y') }} PageTurner Bookstore. All rights reserved.</p>
            <p>123 Book Lane, Reading City, RC 12345</p>
            <p style="margin-top: 15px;">
                <a href="{{ route('home') }}" style="color: #6b7280;">Visit our store</a> •
                <a href="#" style="color: #6b7280;">Unsubscribe</a>
            </p>
        </div>
    </div>
</body>
</html>
