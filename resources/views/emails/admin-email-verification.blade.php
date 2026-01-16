<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Email Change Verification</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #1a1a1a;
            font-size: 24px;
            margin: 0;
        }

        .header .brand {
            color: #2563eb;
        }

        .code-box {
            background-color: #f3f4f6;
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }

        .code {
            font-size: 36px;
            font-weight: bold;
            color: #2563eb;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
        }

        .info-box {
            background-color: #dbeafe;
            border-left: 4px solid #2563eb;
            padding: 12px 16px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }

        .info-box p {
            margin: 0;
            color: #1e40af;
            font-size: 14px;
        }

        .warning {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 12px 16px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }

        .warning p {
            margin: 0;
            color: #92400e;
            font-size: 14px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>MHR <span class="brand">Admin</span></h1>
        </div>

        <p>Hello <strong>{{ $userName }}</strong>,</p>

        @if ($verificationType === 'old_email')
            <p>We received a request to change your admin account email address. To verify this is you, please enter the
                following verification code:</p>

            @if ($newEmail)
                <div class="info-box">
                    <p><strong>New email requested:</strong> {{ $newEmail }}</p>
                </div>
            @endif
        @else
            <p>You're almost done! Please verify your new email address by entering the following verification code:</p>
        @endif

        <div class="code-box">
            <div class="code">{{ $verificationCode }}</div>
        </div>

        <div class="warning">
            <p><strong>Important:</strong> This code will expire in 10 minutes. Do not share this code with anyone.</p>
        </div>

        <p>If you did not request this email change, please ignore this email and ensure your account is secure.</p>

        <div class="footer">
            <p>This email was sent automatically by MHR Admin system.</p>
            <p>&copy; {{ date('Y') }} MHR Clothing. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
