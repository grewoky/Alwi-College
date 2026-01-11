@php
    $isTeacher = strtolower($userType) === 'guru';
    $dateFormatted = \Carbon\Carbon::parse($scheduleInfo['date'])
        ->locale('id')
        ->translatedFormat('l, d F Y');
@endphp

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $isTeacher ? 'Anda Dijadwalkan Mengajar' : 'Jadwal Pelajaran Baru' }}</title>
</head>
<body style="margin:0; padding:0; background:#f5f7fb; font-family: Arial, Helvetica, sans-serif; color:#111827;">
    <div style="max-width: 640px; margin: 0 auto; padding: 24px;">
        <div style="background:#ffffff; border:1px solid #e5e7eb; border-radius: 12px; padding: 24px;">
            <h2 style="margin:0 0 12px; font-size: 20px; line-height: 1.25;">
                @if($isTeacher)
                    Anda Dijadwalkan Mengajar
                @else
                    Jadwal Pelajaran Baru
                @endif
            </h2>

            <p style="margin:0 0 16px; font-size: 14px; line-height: 1.6;">
                Halo <strong>{{ $recipientName }}</strong>,
            </p>

            <p style="margin:0 0 18px; font-size: 14px; line-height: 1.6;">
                @if($isTeacher)
                    Anda memiliki jadwal mengajar baru yang telah dibuat oleh administrator.
                @else
                    Jadwal pelajaran baru telah ditambahkan untuk sekolah Anda.
                @endif
            </p>

            <div style="background:#f9fafb; border:1px solid #e5e7eb; border-radius: 10px; padding: 16px; margin: 0 0 18px;">
                <p style="margin:0 0 8px; font-size: 14px;"><strong>Detail Jadwal</strong></p>
                <p style="margin:0 0 6px; font-size: 13px;"><strong>Tanggal:</strong> {{ $dateFormatted }}</p>
                <p style="margin:0 0 6px; font-size: 13px;"><strong>Mata Pelajaran:</strong> {{ $scheduleInfo['subject_name'] ?? '-' }}</p>
                <p style="margin:0 0 6px; font-size: 13px;"><strong>Pengajar:</strong> {{ $scheduleInfo['teacher_name'] ?? '-' }}</p>
                <p style="margin:0 0 6px; font-size: 13px;"><strong>Kelas:</strong> {{ $scheduleInfo['class_name'] ?? '-' }}</p>
                <p style="margin:0 0 6px; font-size: 13px;"><strong>Sekolah:</strong> {{ $scheduleInfo['school_name'] ?? '-' }}</p>
                <p style="margin:0; font-size: 13px;">
                    <strong>Jam:</strong>
                    @if(!empty($scheduleInfo['start_time']) && !empty($scheduleInfo['end_time']))
                        {{ $scheduleInfo['start_time'] }} - {{ $scheduleInfo['end_time'] }}
                    @else
                        (Belum ditentukan)
                    @endif
                </p>
            </div>

            <p style="margin:0 0 10px; font-size: 14px; line-height: 1.6;"><strong>Langkah selanjutnya:</strong></p>
            <ol style="margin:0 0 18px 18px; padding:0; font-size: 14px; line-height: 1.7;">
                @if($isTeacher)
                    <li>Login ke sistem {{ $appName }}</li>
                    <li>Buka menu <strong>Jadwal Mengajar</strong> untuk melihat detail lengkap</li>
                    <li>Siapkan materi pelajaran</li>
                    <li>Hadir tepat waktu sesuai jadwal</li>
                @else
                    <li>Login ke sistem {{ $appName }}</li>
                    <li>Buka menu <strong>Jadwal Pelajaran</strong> untuk melihat semua jadwal Anda</li>
                    <li>Catat tanggal dan waktu pelajaran</li>
                    <li>Pastikan Anda siap untuk menghadiri</li>
                @endif
            </ol>

            <p style="margin:0 0 18px; font-size: 13px; line-height: 1.6; color:#374151;">
                Jika ada pertanyaan, silakan hubungi administrator.
            </p>

            <a href="{{ $appUrl }}" style="display:inline-block; background:#2563eb; color:#ffffff; text-decoration:none; padding: 12px 16px; border-radius: 10px; font-size: 14px;">
                Buka Sistem
            </a>

            <p style="margin:18px 0 0; font-size: 12px; color:#6b7280;">
                Terima kasih,<br>
                {{ $appName }}
            </p>
        </div>
    </div>
</body>
</html>
