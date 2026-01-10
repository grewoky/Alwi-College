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
        .credentials {
            background-color: #f9f9f9;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            font-size: 13px;
        }
        .credentials-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .credentials-row:last-child {
            margin-bottom: 0;
        }
        .credentials-label {
            font-weight: 600;
            color: #333;
        }
        .credentials-value {
            font-family: monospace;
            color: #667eea;
            word-break: break-all;
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
        .instructions strong {
            display: block;
            margin-bottom: 10px;
            color: #0056b3;
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
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>✅ Akun {{ $userTypeLabel }} Dibuat</h1>
            <p>Alwi College - Sistem Manajemen Sekolah</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Halo <strong>{{ $userName }}</strong>,
            </div>

            <div class="message">
                <p>Akun {{ $userType === 'guru' ? 'guru' : 'siswa' }} Anda telah berhasil dibuat oleh admin sistem. Berikut adalah informasi akun Anda:</p>
            </div>

            <!-- Credentials -->
            <div class="credentials">
                <div class="credentials-row">
                    <span class="credentials-label">Email:</span>
                    <span class="credentials-value">{{ $userEmail }}</span>
                </div>
                <div class="credentials-row">
                    <span class="credentials-label">Password:</span>
                    <span class="credentials-value">{{ $password }}</span>
                </div>
            </div>

            <!-- Instructions -->
            <div class="instructions">
                <strong>📋 Cara Login:</strong>
                <ol>
                    <li>Buka aplikasi Alwi College</li>
                    <li>Klik "Login" atau "Masuk"</li>
                    <li>Masukkan Email: <strong>{{ $userEmail }}</strong></li>
                    <li>Masukkan Password di atas</li>
                    <li>Klik "Login" atau "Masuk"</li>
                    <li>Dashboard Anda akan terbuka</li>
                </ol>
            </div>

            <div class="message">
                <p><strong>⚠️ Penting:</strong></p>
                <ul style="margin-left: 20px; margin-top: 10px;">
                    <li>Simpan password ini dengan aman</li>
                    <li>Anda dapat mengubah password setelah login</li>
                    <li>Jangan bagikan password kepada siapa pun</li>
                    <li>Jika ada pertanyaan, hubungi admin sekolah</li>
                </ul>
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
