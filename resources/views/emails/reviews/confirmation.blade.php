<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Confirmation - PageTurner</title>
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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
            text-align: center;
        }
        .thank-you {
            font-size: 32px;
            color: #10b981;
            margin-bottom: 20px;
        }
        .message {
            color: #4b5563;
            font-size: 16px;
            margin: 20px 0;
        }
        .book-info {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background-color: #4f46e5;
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
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
            <div class="logo">📚 PageTurner</div>
        </div>

        <div class="content">
            <div class="thank-you">Thank You!</div>

            <div class="message">
                Your review for <strong>{{ $review->book->title }}</strong> has been submitted successfully.
            </div>

            <div class="book-info">
                <div style="margin-bottom: 15px;">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $review->rating)
                            <span style="color: #fbbf24; font-size: 24px;">★</span>
                        @else
                            <span style="color: #d1d5db; font-size: 24px;">★</span>
                        @endif
                    @endfor
                </div>

                <p style="font-style: italic; color: #4b5563;">"{{ $review->comment }}"</p>
            </div>

            <p class="message">
                Your review helps other readers discover great books!
            </p>

            <a href="{{ route('books.show', $review->book) }}" class="button">
                View Your Review
            </a>
        </div>

        <div class="footer">
            <p>Thank you for being part of the PageTurner community!</p>
            <p>Happy Reading! 📖</p>
        </div>
    </div>
</body>
</html>
