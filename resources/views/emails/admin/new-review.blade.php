<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Review Alert - PageTurner Admin</title>
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
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
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
        .book-card {
            display: flex;
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .book-cover {
            width: 60px;
            height: 80px;
            background-color: #e5e7eb;
            border-radius: 4px;
            margin-right: 15px;
            overflow: hidden;
        }
        .rating {
            color: #fbbf24;
            font-size: 20px;
            margin: 10px 0;
        }
        .review-content {
            background-color: #f3f4f6;
            padding: 20px;
            border-radius: 8px;
            font-style: italic;
            margin: 15px 0;
        }
        .admin-button {
            display: inline-block;
            background-color: #1f2937;
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
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
        </div>

        <div class="content">
            <h2 style="color: #1f2937;">New Review Submitted</h2>

            <div class="book-card">
                <div class="book-cover">
                    @if($review->book->cover_image)
                        <img src="{{ asset('storage/' . $review->book->cover_image) }}"
                             style="width: 100%; height: 100%; object-fit: cover;">
                    @endif
                </div>
                <div>
                    <h3 style="margin: 0;">{{ $review->book->title }}</h3>
                    <p style="color: #6b7280; margin: 5px 0;">by {{ $review->book->author }}</p>
                </div>
            </div>

            <div class="rating">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= $review->rating)
                        ★
                    @else
                        ☆
                    @endif
                @endfor
                <span style="color: #6b7280; margin-left: 10px;">({{ $review->rating }}/5)</span>
            </div>

            <p><strong>Reviewer:</strong> {{ $review->user->name }} ({{ $review->user->email }})</p>

            <div class="review-content">
                "{{ $review->comment }}"
            </div>

            <p style="color: #6b7280;">Submitted {{ $review->created_at->diffForHumans() }}</p>

            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ route('admin.books.reviews', $review->book) }}" class="admin-button">
                    Manage Reviews
                </a>
            </div>
        </div>

        <div class="footer">
            <p>New review notification from PageTurner Bookstore</p>
        </div>
    </div>
</body>
</html>
