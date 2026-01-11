@php
    $isTeacher = strtolower($userType) === 'guru';
    $dateFormatted = \Carbon\Carbon::parse($scheduleInfo['date'])
        ->locale('id')
        ->translatedFormat('l, d F Y');
@endphp

<x-mail::message>
# @if($isTeacher)
Anda Dijadwalkan Mengajar 🎓
@else
Jadwal Pelajaran Baru 📅
@endif

Halo **{{ $recipientName }}**,

@if($isTeacher)
Anda memiliki jadwal mengajar baru yang telah dibuat oleh administrator.
@else
Jadwal pelajaran baru telah ditambahkan untuk sekolah Anda.
@endif

## Detail Jadwal

<x-mail::panel>
**Tanggal:** {{ $dateFormatted }}

**Mata Pelajaran:** {{ $scheduleInfo['subject_name'] ?? '-' }}

**Pengajar:** {{ $scheduleInfo['teacher_name'] ?? '-' }}

**Kelas:** {{ $scheduleInfo['class_name'] ?? '-' }}

**Sekolah:** {{ $scheduleInfo['school_name'] ?? '-' }}

**Jam:** 
@if($scheduleInfo['start_time'] && $scheduleInfo['end_time'])
{{ $scheduleInfo['start_time'] }} - {{ $scheduleInfo['end_time'] }}
@else
(Belum ditentukan)
@endif
</x-mail::panel>

## Langkah Selanjutnya

@if($isTeacher)
1. Login ke sistem {{ $appName }}
2. Buka menu **Jadwal Mengajar** untuk melihat detail lengkap
3. Siapkan materi pelajaran
4. Hadir tepat waktu sesuai jadwal yang telah ditentukan
@else
1. Login ke sistem {{ $appName }}
2. Buka menu **Jadwal Pelajaran** untuk melihat semua jadwal Anda
3. Catat tanggal dan waktu pelajaran
4. Pastikan Anda siap untuk menghadiri
@endif

---

Jika ada pertanyaan, silakan hubungi administrator.

@component('mail::button', ['url' => $appUrl, 'color' => 'primary'])
Buka Sistem
@endcomponent

Terima kasih,<br>
{{ $appName }}
</x-mail::message>
