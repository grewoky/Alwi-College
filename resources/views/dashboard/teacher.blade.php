<x-app-layout>
  <x-slot name="title">Guru • Dashboard</x-slot>

  <!-- Hero Header Section -->
  <div class="mb-8 bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 rounded-2xl p-8 text-white shadow-lg">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-4xl font-bold mb-2">Selamat Datang Guru</h1>
        <p class="text-green-100 text-lg">Kelola jadwal mengajar, absensi, dan trip dengan mudah</p>
      </div>
      <div class="text-6xl hidden md:block">👨‍🏫</div>
    </div>
  </div>

  <!-- Key Statistics -->
  <div class="grid md:grid-cols-2 gap-6 mb-8">
    <!-- Today's Lessons -->
    <div class="bg-gradient-to-br from-green-50 to-emerald-100 border-2 border-green-200 rounded-xl p-6 shadow-md hover:shadow-lg transition-all">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-semibold text-green-600 uppercase">📅 Jadwal Hari Ini</p>
          <p class="text-5xl font-bold text-green-900 mt-2">{{ $todayLessons }}</p>
          <p class="text-sm text-green-700 mt-1">jam pelajaran</p>
        </div>
        <div class="text-6xl">�</div>
      </div>
    </div>

    <!-- Monthly Trips -->
    <div class="bg-gradient-to-br from-orange-50 to-amber-100 border-2 border-orange-200 rounded-xl p-6 shadow-md hover:shadow-lg transition-all">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-semibold text-orange-600 uppercase">🚗 Trip Bulan Ini</p>
          <p class="text-5xl font-bold text-orange-900 mt-2">{{ $thisMonthTrips }} <span class="text-2xl">/</span> 90</p>
          <p class="text-sm text-orange-700 mt-1">poin perbulan</p>
        </div>
        <div class="text-6xl">🚗</div>
      </div>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="mb-12">
    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
      <svg class="w-7 h-7 text-green-600" fill="currentColor" viewBox="0 0 20 20">
        <path d="M10.5 1.5H5.75A2.25 2.25 0 003.5 3.75v12.5A2.25 2.25 0 005.75 18.5h8.5a2.25 2.25 0 002.25-2.25V6.5m-11-4v4m0 0h4m-4 0L16 14.5"/>
      </svg>
      Akses Cepat
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- View Schedule -->
      <a href="{{ route('lessons.teacher') }}" class="group bg-gradient-to-br from-green-50 to-emerald-100 border-2 border-green-200 rounded-xl p-6 shadow-md hover:shadow-xl hover:scale-105 transition-all duration-300 transform">
        <div class="flex items-start gap-4">
          <div class="text-5xl">📝</div>
          <div class="flex-grow">
            <h3 class="text-lg font-bold text-green-900 mb-2">Lihat Jadwal</h3>
            <p class="text-sm text-green-700">Absen kelas & kelola kehadiran siswa</p>
            <div class="text-green-600 font-semibold text-sm mt-3 group-hover:text-green-700">Akses →</div>
          </div>
        </div>
      </a>

      <!-- Attendance -->
      <a href="{{ route('attendance.teacher') }}" class="group bg-gradient-to-br from-emerald-50 to-teal-100 border-2 border-emerald-200 rounded-xl p-6 shadow-md hover:shadow-xl hover:scale-105 transition-all duration-300 transform">
        <div class="flex items-start gap-4">
          <div class="text-5xl">✓</div>
          <div class="flex-grow">
            <h3 class="text-lg font-bold text-emerald-900 mb-2">Absensi</h3>
            <p class="text-sm text-emerald-700">Rekap kehadiran & laporan siswa</p>
            <div class="text-emerald-600 font-semibold text-sm mt-3 group-hover:text-emerald-700">Akses →</div>
          </div>
        </div>
      </a>

      <!-- Student Documents -->
      <a href="{{ route('info.teacher.student-files') }}" class="group bg-gradient-to-br from-amber-50 to-yellow-100 border-2 border-amber-200 rounded-xl p-6 shadow-md hover:shadow-xl hover:scale-105 transition-all duration-300 transform">
        <div class="flex items-start gap-4">
          <div class="text-5xl">📚</div>
          <div class="flex-grow">
            <h3 class="text-lg font-bold text-amber-900 mb-2">Dokumen</h3>
            <p class="text-sm text-amber-700">Lihat file yang diupload siswa</p>
            <div class="text-amber-600 font-semibold text-sm mt-3 group-hover:text-amber-700">Akses →</div>
          </div>
        </div>
      </a>
    </div>
  </div>
</x-app-layout>
