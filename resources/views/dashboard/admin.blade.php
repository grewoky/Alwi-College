<x-admin-layout title="Admin • Dashboard">
  <!-- Hero Header Section -->
  <div class="mb-8 bg-gradient-to-r from-indigo-600 via-indigo-700 to-purple-700 rounded-2xl p-8 text-white shadow-lg">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-4xl font-bold mb-2">Welcome Admin</h1>
        <p class="text-indigo-100 text-lg">Kelola sistem pembelajaran Alwi College dengan mudah</p>
      </div>
      <div class="text-6xl hidden md:block">🎓</div>
    </div>
  </div>

  <!-- Statistics Cards -->
  <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- Students Stat -->
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 shadow-md border border-blue-200 hover:shadow-lg transition-all">
      <div class="flex justify-between items-start">
        <div>
          <p class="text-sm font-semibold text-blue-600 uppercase">Total Siswa</p>
          <p class="text-4xl font-bold text-blue-900 mt-2">{{ $stats['students'] }}</p>
          <p class="text-xs text-blue-700 mt-2">📈 siswa aktif</p>
        </div>
        <div class="text-4xl">👨‍🎓</div>
      </div>
    </div>

    <!-- Teachers Stat -->
    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 shadow-md border border-green-200 hover:shadow-lg transition-all">
      <div class="flex justify-between items-start">
        <div>
          <p class="text-sm font-semibold text-green-600 uppercase">Total Guru</p>
          <p class="text-4xl font-bold text-green-900 mt-2">{{ $stats['teachers'] }}</p>
          <p class="text-xs text-green-700 mt-2">👨‍🏫 pengajar</p>
        </div>
        <div class="text-4xl">👨‍🏫</div>
      </div>
    </div>

    <!-- Classes Stat -->
    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 shadow-md border border-purple-200 hover:shadow-lg transition-all">
      <div class="flex justify-between items-start">
        <div>
          <p class="text-sm font-semibold text-purple-600 uppercase">Jadwal Hari Ini</p>
          <p class="text-4xl font-bold text-purple-900 mt-2">{{ $stats['today_lessons'] }}</p>
          <p class="text-xs text-purple-700 mt-2">📅 jadwal pelajaran</p>
        </div>
        <div class="text-4xl">📚</div>
      </div>
    </div>

    <!-- Pending Payments Stat -->
    <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-6 shadow-md border border-orange-200 hover:shadow-lg transition-all">
      <div class="flex justify-between items-start">
        <div>
          <p class="text-sm font-semibold text-orange-600 uppercase">Pembayaran Pending</p>
          <p class="text-4xl font-bold text-orange-900 mt-2">{{ $stats['payments_pending'] }}</p>
          <p class="text-xs text-orange-700 mt-2">⏳ menunggu verifikasi</p>
        </div>
        <div class="text-4xl">💳</div>
      </div>
    </div>
  </div>

  <!-- Quick Actions - Grid -->
  <div class="mb-12">
    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
      <svg class="w-7 h-7 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
        <path d="M10.5 1.5H5.75A2.25 2.25 0 003.5 3.75v12.5A2.25 2.25 0 005.75 18.5h8.5a2.25 2.25 0 002.25-2.25V6.5m-11-4v4m0 0h4m-4 0L16 14.5"/>
      </svg>
      Akses Cepat
    </h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <!-- View Jadwal -->
      <a href="{{ route('lessons.admin.dashboard') }}" class="group bg-gradient-to-br from-blue-50 to-blue-100 border-2 border-blue-200 rounded-xl p-6 shadow-md hover:shadow-xl hover:scale-105 transition-all duration-300 transform">
        <div class="flex flex-col h-full">
          <div class="text-5xl mb-3">📚</div>
          <h3 class="text-lg font-bold text-blue-900 mb-2">Jadwal Pelajaran</h3>
          <p class="text-sm text-blue-700 flex-grow">Lihat & kelola jadwal les</p>
          <div class="text-blue-600 font-semibold text-sm mt-3 group-hover:text-blue-700">Akses →</div>
        </div>
      </a>

      <!-- Generate Jadwal -->
      <a href="{{ route('lessons.generate.form') }}" class="group bg-gradient-to-br from-cyan-50 to-cyan-100 border-2 border-cyan-200 rounded-xl p-6 shadow-md hover:shadow-xl hover:scale-105 transition-all duration-300 transform">
        <div class="flex flex-col h-full">
          <div class="text-5xl mb-3">📅</div>
          <h3 class="text-lg font-bold text-cyan-900 mb-2">Generate Jadwal</h3>
          <p class="text-sm text-cyan-700 flex-grow">Buat jadwal baru otomatis</p>
          <div class="text-cyan-600 font-semibold text-sm mt-3 group-hover:text-cyan-700">Akses →</div>
        </div>
      </a>

      <!-- Info Management -->
      <a href="{{ route('info.admin.list') }}" class="group bg-gradient-to-br from-purple-50 to-purple-100 border-2 border-purple-200 rounded-xl p-6 shadow-md hover:shadow-xl hover:scale-105 transition-all duration-300 transform">
        <div class="flex flex-col h-full">
          <div class="text-5xl mb-3">📋</div>
          <h3 class="text-lg font-bold text-purple-900 mb-2">Info & File</h3>
          <p class="text-sm text-purple-700 flex-grow">Kelola file pembelajaran</p>
          <div class="text-purple-600 font-semibold text-sm mt-3 group-hover:text-purple-700">Akses →</div>
        </div>
      </a>

      <!-- Trip Management -->
      <a href="{{ route('trips.index') }}" class="group bg-gradient-to-br from-green-50 to-green-100 border-2 border-green-200 rounded-xl p-6 shadow-md hover:shadow-xl hover:scale-105 transition-all duration-300 transform">
        <div class="flex flex-col h-full">
          <div class="text-5xl mb-3">🚗</div>
          <h3 class="text-lg font-bold text-green-900 mb-2">Trip Guru</h3>
          <p class="text-sm text-green-700 flex-grow">Kelola 90 poin/bulan</p>
          <div class="text-green-600 font-semibold text-sm mt-3 group-hover:text-green-700">Akses →</div>
        </div>
      </a>

      <!-- Payment Verification -->
      <a href="{{ route('pay.list') }}" class="group bg-gradient-to-br from-orange-50 to-orange-100 border-2 border-orange-200 rounded-xl p-6 shadow-md hover:shadow-xl hover:scale-105 transition-all duration-300 transform">
        <div class="flex flex-col h-full">
          <div class="text-5xl mb-3">💰</div>
          <h3 class="text-lg font-bold text-orange-900 mb-2">Pembayaran</h3>
          <p class="text-sm text-orange-700 flex-grow">Verifikasi pembayaran siswa</p>
          <div class="text-orange-600 font-semibold text-sm mt-3 group-hover:text-orange-700">Akses →</div>
        </div>
      </a>

      <!-- Attendance -->
      <a href="{{ route('attendance.admin') }}" class="group bg-gradient-to-br from-indigo-50 to-indigo-100 border-2 border-indigo-200 rounded-xl p-6 shadow-md hover:shadow-xl hover:scale-105 transition-all duration-300 transform">
        <div class="flex flex-col h-full">
          <div class="text-5xl mb-3">✓</div>
          <h3 class="text-lg font-bold text-indigo-900 mb-2">Absensi</h3>
          <p class="text-sm text-indigo-700 flex-grow">Laporan kehadiran siswa</p>
          <div class="text-indigo-600 font-semibold text-sm mt-3 group-hover:text-indigo-700">Akses →</div>
        </div>
      </a>
    </div>
  </div>
</x-admin-layout>
