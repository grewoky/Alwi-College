<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Welcome -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900">🎓 Selamat Datang, {{ auth()->user()->name }}!</h1>
                <p class="text-gray-600 mt-2">Selamat datang di Alwi College</p>
            </div>

            <!-- About Image -->
            <div class="mb-8">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="{{ asset('images/About_Alwi.png') }}" alt="About Alwi College" class="w-full h-auto">
                </div>
            </div>

            <!-- Quick Links - Diperbesar -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="{{ route('lessons.index') }}" class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-8 border-t-4 border-blue-600 hover:scale-105 transform transition-transform duration-200">
                    <div class="text-6xl mb-4">📅</div>
                    <h3 class="text-2xl font-bold text-gray-900">Jadwal Les</h3>
                    <p class="text-gray-600 mt-2">Lihat jadwal pelajaran Anda</p>
                </a>

                <a href="{{ route('info.index') }}" class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-8 border-t-4 border-green-600 hover:scale-105 transform transition-transform duration-200">
                    <div class="text-6xl mb-4">📋</div>
                    <h3 class="text-2xl font-bold text-gray-900">Info</h3>
                    <p class="text-gray-600 mt-2">Unggah kisi-kisi materi</p>
                </a>

                <a href="{{ route('attendance.index') }}" class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-8 border-t-4 border-purple-600 hover:scale-105 transform transition-transform duration-200">
                    <div class="text-6xl mb-4">✓</div>
                    <h3 class="text-2xl font-bold text-gray-900">Absensi</h3>
                    <p class="text-gray-600 mt-2">Lihat riwayat absensi Anda</p>
                </a>

                <a href="{{ route('pay.index') }}" class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-8 border-t-4 border-orange-600 hover:scale-105 transform transition-transform duration-200">
                    <div class="text-6xl mb-4">💳</div>
                    <h3 class="text-2xl font-bold text-gray-900">Pembayaran</h3>
                    <p class="text-gray-600 mt-2">Verifikasi pembayaran</p>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
