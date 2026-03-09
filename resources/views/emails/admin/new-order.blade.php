<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Alert - PageTurner Admin</title>
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
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            padding: 30px 20px;
            text-align: center;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: white;
        }
        .admin-badge {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 12px;
            display: inline-block;
            margin-top: 10px;
        }
        .content {
            padding: 40px 30px;
        }
        .alert-title {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #fee2e2;
        }
        .customer-card {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .customer-info {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .customer-avatar {
            width: 50px;
            height: 50px;
            background-color: #4f46e5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 20px;
            margin-right: 15px;
        }
        .customer-details h4 {
            margin: 0;
            color: #1f2937;
            font-size: 18px;
        }
        .customer-details p {
            margin: 5px 0 0;
            color: #6b7280;
        }
        .order-highlight {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        .order-highlight .amount {
            font-size: 36px;
            font-weight: bold;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .items-table th {
            background-color: #f3f4f6;
            padding: 12px;
            text-align: left;
            font-size: 14px;
            color: #4b5563;
        }
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        .urgent-action {
            background-color: #fee2e2;
            border-left: 4px solid #dc2626;
            padding: 15px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .admin-button {
            display: inline-block;
            background-color: #1f2937;
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            margin: 10px 5px;
        }
        .footer {
            padding: 30px;
            text-align: center;
            background-color: #f9fafb;
            color: #9ca3af;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">📚 PageTurner Admin</div>
            <div class="admin-badge">Admin Alert</div>
        </div>

        <div class="content">
            <div class="alert-title">
                🚨 New Order Received
            </div>

            <div class="customer-card">
                <div class="customer-info">
                    <div class="customer-avatar">
                        {{ strtoupper(substr($order->user->name, 0, 1)) }}
                    </div>
                    <div class="customer-details">
                        <h4>{{ $order->user->name }}</h4>
                        <p>{{ $order->user->email }}</p>
                        <p>Customer since: {{ $order->user->created_at->format('M Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="order-highlight">
                <div style="font-size: 14px; margin-bottom: 5px;">Order Total</div>
                <div class="amount">${{ number_format($order->total_amount, 2) }}</div>
                <div style="font-size: 12px; margin-top: 5px;">Order #{{ $order->id }}</div>
            </div>

            <h3 style="color: #1f2937; margin: 20px 0 10px;">Order Items</h3>

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
                        <td>{{ $item->book->title }}</td>
                        <td>{{ $item->book->author }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->unit_price, 2) }}</td>
                        <td>${{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin: 20px 0;">
                <p><strong>Shipping Address:</strong><br>
                {{ $order->shipping_address ?? 'Not specified' }}</p>

                <p><strong>Payment Method:</strong> {{ $order->payment_method ?? 'Credit Card' }}</p>

                <p><strong>Order Notes:</strong><br>
                {{ $order->notes ?? 'No special instructions' }}</p>
            </div>

            <div class="urgent-action">
                <strong>⚠️ Inventory Check Required</strong><br>
                Please verify stock levels for the items above and process this order.
            </div>

            <div style="text-align: center;">
                <a href="{{ route('orders.show', $order) }}" class="admin-button">
                    View Order in Admin
                </a>
                <a href="mailto:{{ $order->user->email }}" class="admin-button" style="background-color: #4b5563;">
                    Contact Customer
                </a>
            </div>
        </div>

        <div class="footer">
            <p>This is an automated admin notification from PageTurner Bookstore.</p>

        </div>
    </div>
</body>
</html>
