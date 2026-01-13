<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Invitation</title>
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
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #1a1a1a;
        }

        .logo span {
            color: #3b82f6;
        }

        h1 {
            color: #1a1a1a;
            font-size: 24px;
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 15px;
            color: #555;
        }

        .btn {
            display: inline-block;
            background-color: #3b82f6;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }

        .btn:hover {
            background-color: #2563eb;
        }

        .btn-container {
            text-align: center;
        }

        .info-box {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }

        .info-box p {
            margin: 0;
            color: #92400e;
            font-size: 14px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            font-size: 13px;
            color: #888;
        }

        .url-fallback {
            word-break: break-all;
            font-size: 12px;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo">MHR <span>Admin</span></div>
        </div>

        <h1>You're Invited! 🎉</h1>

        <p>Hello,</p>

        <p><strong>{{ $inviterName }}</strong> has invited you to join the MHR Admin team. As an admin, you'll have
            access to manage products, orders, customers, and more.</p>

        <div class="btn-container">
            <a href="{{ $acceptUrl }}" class="btn">Accept Invitation</a>
        </div>

        <div class="info-box">
            <p>⏰ This invitation will expire on <strong>{{ $expiresAt }}</strong>. Please accept it before then.</p>
        </div>

        <p>When you accept the invitation, you'll be asked to complete your registration with your name, WhatsApp
            number, and create a password.</p>

        <p class="url-fallback">
            If the button doesn't work, copy and paste this URL into your browser:<br>
            <a href="{{ $acceptUrl }}">{{ $acceptUrl }}</a>
        </p>

        <div class="footer">
            <p>This email was sent by MHR Clothing Admin System.<br>
                If you didn't expect this invitation, please ignore this email.</p>
        </div>
    </div>
</body>

</html>
