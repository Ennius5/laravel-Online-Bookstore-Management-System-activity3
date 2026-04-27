<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Update - PageTurner</title>
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
        .status-update {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 25px;
            margin: 25px 0;
            text-align: center;
        }
        .old-status {
            display: inline-block;
            padding: 8px 16px;
            background-color: #e5e7eb;
            color: #4b5563;
            border-radius: 9999px;
            font-size: 14px;
            margin: 0 10px;
        }
        .new-status {
            display: inline-block;
            padding: 8px 16px;
            background-color: #10b981;
            color: white;
            border-radius: 9999px;
            font-size: 14px;
            font-weight: bold;
            margin: 0 10px;
        }
        .arrow {
            font-size: 20px;
            color: #9ca3af;
        }
        @php
            $statusMessages = [
                'processing' => 'Your order is now being processed and packed.',
                'shipped' => 'Great news! Your order has been shipped and is on its way.',
                'completed' => 'Your order has been completed. Thank you for shopping with us!',
                'cancelled' => 'Your order has been cancelled. Please contact support if this was a mistake.'
            ];
        @endphp
        .button {
            display: inline-block;
            background-color: #131223;
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 20px;
        }
        .footer {
            padding: 30px;
            text-align: center;
            background-color: #f9fafb;
            color: #9ca3af;
            font-size: 14px;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">📚 PageTurner</div>
        </div>

        <div class="content">
            <div class="greeting">
                Order Status Update, {{ $order->user->name }}!
            </div>

            <div class="message">
                Your order #{{ $order->id }} has been updated.
            </div>

            <div class="status-update">
                <span class="old-status">{{ ucfirst($oldStatus) }}</span>
                <span class="arrow">→</span>
                <span class="new-status">{{ ucfirst($order->status) }}</span>
            </div>

            <p style="font-size: 16px; color: #4b5563; text-align: center; margin: 20px 0;">
                {{ $statusMessages[$order->status] ?? 'Your order status has been updated.' }}
            </p>

            <div style="text-align: center;">
                <a class="text-amber-600 mt-1" href="{{ route('orders.show', $order) }}" class="button">
                    Track Your Order
                </a>
                <a class="text-blue-600 mt-1" href="{{ route('orders.invoice',['order' => $order->id]) }}">
                    Get Invoice
                </a>
                </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} PageTurner Bookstore</p>
        </div>
    </div>
</body>
</html>
