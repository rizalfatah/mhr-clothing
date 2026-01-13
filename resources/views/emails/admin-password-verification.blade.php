<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Perubahan Password</title>
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

        <p>Halo <strong>{{ $userName }}</strong>,</p>

        <p>Kami menerima permintaan untuk mengubah password akun admin Anda. Gunakan kode verifikasi berikut untuk
            melanjutkan:</p>

        <div class="code-box">
            <div class="code">{{ $verificationCode }}</div>
        </div>

        <div class="warning">
            <p><strong>Perhatian:</strong> Kode ini akan kedaluwarsa dalam 10 menit. Jangan bagikan kode ini kepada
                siapapun.</p>
        </div>

        <p>Jika Anda tidak meminta perubahan password, abaikan email ini dan pastikan akun Anda aman.</p>

        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh sistem MHR Admin.</p>
            <p>&copy; {{ date('Y') }} MHR Clothing. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
