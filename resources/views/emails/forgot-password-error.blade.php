<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Error - Lupa Password</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
            margin: -20px -20px 20px -20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .content {
            padding: 20px 0;
        }
        .error-box {
            background-color: #fee;
            border-left: 4px solid #dc2626;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .error-code {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            color: #666;
            margin-top: 10px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 15px;
            margin: 20px 0;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
        }
        .info-label {
            font-weight: bold;
            color: #555;
        }
        .info-value {
            color: #333;
            word-break: break-all;
        }
        .divider {
            border-top: 1px solid #e0e0e0;
            margin: 20px 0;
        }
        .action-button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 15px;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            font-size: 12px;
            color: #999;
            text-align: center;
        }
        .warning-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="warning-icon">⚠️</div>
            <h1>Notifikasi Error</h1>
            <p style="margin: 5px 0 0 0;">Lupa Password</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Halo Admin {{ $appName }},</p>

            <p>Ada error yang terjadi pada sistem lupa password. Berikut adalah detail informasinya:</p>

            <!-- Error Details -->
            <div class="error-box">
                <strong>❌ Error Message:</strong>
                <p style="margin: 10px 0 0 0; color: #dc2626; font-weight: 500;">
                    {{ $errorMessage }}
                </p>
                <div class="error-code">
                    <strong>Error Code:</strong> {{ $errorCode }}
                </div>
            </div>

            <!-- Info Grid -->
            <div class="info-grid">
                <div class="info-label">📧 Email Pengguna:</div>
                <div class="info-value">{{ $email }}</div>

                <div class="info-label">🕐 Waktu Terjadinya:</div>
                <div class="info-value">{{ $timestamp }}</div>

                <div class="info-label">🌍 Aplikasi:</div>
                <div class="info-value">{{ $appName }}</div>

                <div class="info-label">🔗 URL:</div>
                <div class="info-value">{{ $appUrl }}</div>
            </div>

            <div class="divider"></div>

            <p><strong>Tindakan Rekomendasi:</strong></p>
            <ul style="margin: 10px 0;">
                <li>Periksa konfigurasi email sistem</li>
                <li>Verifikasi kredensial email (username, password, SMTP)</li>
                <li>Pastikan user dengan email ini terdaftar dalam sistem</li>
                <li>Cek log aplikasi untuk detail error lebih lengkap</li>
                <li>Hubungi developer jika error terus berlanjut</li>
            </ul>

            <div class="divider"></div>

            <p>
                <a href="{{ $appUrl }}" class="action-button">
                    🔐 Buka Aplikasi
                </a>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>⚙️ Email ini dikirim secara otomatis oleh sistem {{ $appName }}</p>
            <p>Jangan reply email ini. Untuk bantuan, hubungi tim teknis.</p>
            <p style="margin-top: 10px;">© {{ date('Y') }} {{ $appName }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
