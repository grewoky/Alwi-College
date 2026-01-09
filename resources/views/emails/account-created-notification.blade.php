@php
$isTeacher = strtolower($userRole) === 'guru' || strtolower($userRole) === 'teacher';
@endphp

<x-mail::message>
# Selamat! Akun Anda Telah Dibuat

Halo **{{ $userName }}**,

Akun Anda telah berhasil dibuat oleh administrator **{{ $appName }}**. Berikut adalah detail login Anda:

## Informasi Login

<x-mail::panel>
**Email:** {{ $userEmail }}

**Password:** {{ $userPassword }}

**Tipe Akun:** {{ $isTeacher ? 'Guru' : 'Siswa' }}
</x-mail::panel>

## Langkah Selanjutnya

1. Kunjungi [{{ $appName }}]({{ $loginUrl }})
2. Masukkan email dan password di atas
3. Setelah login, silakan **ubah password Anda** untuk keamanan
4. Lengkapi data profil Anda jika diperlukan

## Catatan Penting

⚠️ **KEAMANAN:** Jangan bagikan password ini kepada orang lain. Simpan dengan aman.

✅ **Akun Aktif:** Akun Anda sudah aktif dan siap digunakan.

---

Jika Anda mengalami kesulitan, silakan hubungi administrator.

@component('mail::button', ['url' => $loginUrl, 'color' => 'primary'])
Masuk ke Sistem
@endcomponent

Terima kasih,<br>
{{ $appName }}
</x-mail::message>
