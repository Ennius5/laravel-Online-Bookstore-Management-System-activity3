<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Authentication Code</title>
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
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid #e8e8e8;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #4f46e5;
        }
        .content {
            padding: 30px 20px;
            text-align: center;
        }
        .code {
            font-size: 36px;
            font-weight: bold;
            color: #4f46e5;
            letter-spacing: 8px;
            padding: 20px;
            background-color: #f4f4f7;
            border-radius: 8px;
            margin: 20px 0;
            font-family: monospace;
        }
        .footer {
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #6b7280;
            border-top: 1px solid #e8e8e8;
        }
        .warning {
            color: #dc2626;
            font-size: 14px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">📚 PageTurner</div>
        </div>

        <div class="content">
            <h1 style="font-size: 24px; margin-bottom: 20px;">Two-Factor Authentication</h1>

            <p style="font-size: 16px; margin-bottom: 10px;">
                Hello,
            </p>

            <p style="font-size: 16px; margin-bottom: 20px;">
                Please use the following verification code to complete your login:
            </p>

            <div class="code">
                {{ $code }}
            </div>

            <p style="font-size: 14px; color: #6b7280;">
                This code will expire in 10 minutes.
            </p>

            <div class="warning">
                ⚠️ If you didn't request this code, please secure your account immediately.
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} PageTurner Bookstore. All rights reserved.</p>
            <p style="margin-top: 10px;">
                This is an automated message, please do not reply.
            </p>
        </div>
    </div>
</body>
</html>
