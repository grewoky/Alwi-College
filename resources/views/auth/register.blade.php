<x-guest-layout>
  <div class="w-full max-w-md">
    <div class="bg-white/90 backdrop-blur border border-gray-200 rounded-2xl shadow-sm p-6 md:p-8 text-center">
      <div class="mx-auto mb-2 h-16 w-16 flex items-center justify-center">
        <img src="{{ asset('images/logo.png') }}" alt="Alwi College Logo" class="w-full h-full object-contain">
      </div>
      <h1 class="text-xl md:text-2xl font-semibold mt-1">Pendaftaran Ditutup</h1>
      <div class="mt-4 text-gray-700">
        Pendaftaran publik untuk akun baru saat ini ditutup. Untuk menambahkan siswa, gunakan menu <strong>Kelola Siswa → Tambah Siswa</strong> di area admin.
      </div>
      <div class="mt-6">
        <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Kembali ke Login</a>
      </div>
    </div>
  </div>
</x-guest-layout>
