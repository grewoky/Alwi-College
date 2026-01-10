<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
            color: #333;
        }
        .greeting strong {
            color: #667eea;
        }
        .message {
            font-size: 14px;
            line-height: 1.8;
            color: #555;
            margin-bottom: 25px;
        }
        .message p {
            margin-bottom: 10px;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 14px 40px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            margin: 25px 0;
            transition: transform 0.2s;
        }
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .reset-link {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 6px;
            word-break: break-all;
            font-size: 12px;
            color: #666;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .reset-link a {
            color: #667eea;
            text-decoration: none;
        }
        .reset-link a:hover {
            text-decoration: underline;
        }
        .expiry-notice {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            font-size: 14px;
            color: #856404;
        }
        .expiry-notice strong {
            color: #d39e00;
        }
        .instructions {
            background-color: #e7f3ff;
            border: 1px solid #b3d9ff;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            font-size: 13px;
            color: #004085;
        }
        .instructions ol {
            margin-left: 20px;
            margin-top: 10px;
        }
        .instructions li {
            margin-bottom: 8px;
        }
        .footer {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eee;
        }
        .footer p {
            margin-bottom: 5px;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .divider {
            height: 1px;
            background-color: #eee;
            margin: 20px 0;
        }
        .security-note {
            background-color: #f0f0f0;
            padding: 12px;
            border-radius: 6px;
            font-size: 12px;
            color: #666;
            margin-top: 20px;
        }
        .security-note strong {
            color: #d32f2f;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🔐 Reset Your Password</h1>
            <p>Alwi College Account Management</p>
        </div>

        <!-- Main Content -->
        <div class="content">
            <div class="greeting">
                Halo <strong>{{ $userName }}</strong>,
            </div>

            <div class="message">
                <p>Kami menerima permintaan untuk mengatur ulang password akun Anda di Alwi College.</p>
                <p>Klik tombol di bawah untuk membuat password baru:</p>
            </div>

            <!-- CTA Button -->
            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="cta-button">Reset Password Sekarang</a>
            </div>

            <!-- Alternative Link -->
            <div class="reset-link">
                <strong>Atau copy link berikut:</strong><br><br>
                <a href="{{ $resetUrl }}">{{ $resetUrl }}</a>
            </div>

            <!-- Expiry Notice -->
            <div class="expiry-notice">
                ⏱️ <strong>Penting:</strong> Link reset password ini hanya berlaku selama {{ $expiresInMinutes }} menit. Setelah itu, Anda harus meminta link reset baru.
            </div>

            <!-- Instructions -->
            <div class="instructions">
                <strong>📋 Langkah-langkah:</strong>
                <ol>
                    <li>Klik tombol "Reset Password Sekarang" di atas</li>
                    <li>Masukkan email Anda</li>
                    <li>Buat password baru yang kuat</li>
                    <li>Konfirmasi password baru Anda</li>
                    <li>Klik "Reset Password"</li>
                    <li>Gunakan password baru untuk login</li>
                </ol>
            </div>

            <!-- Security Note -->
            <div class="security-note">
                <strong>🔒 Keamanan:</strong> Jika Anda tidak meminta reset password ini, silakan abaikan email ini. Link reset password aman dan hanya dapat digunakan oleh pemilik akun yang sah.
            </div>

            <div class="divider"></div>

            <div class="message" style="margin-top: 20px;">
                <p><strong>Ada pertanyaan?</strong></p>
                <p>Jika Anda mengalami masalah dengan reset password, silakan hubungi tim support kami di admin@alwicollege.com</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Alwi College</strong></p>
            <p>Sistem Manajemen Sekolah Digital</p>
            <p style="margin-top: 10px; color: #bbb;">© {{ date('Y') }} Alwi College. Semua hak dilindungi.</p>
        </div>
    </div>
</body>
</html>
