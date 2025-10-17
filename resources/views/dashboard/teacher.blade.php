<x-app-layout>
  <x-slot name="title">Guru • Dashboard</x-slot>

  <!-- Key Statistics -->
  <div class="grid md:grid-cols-2 gap-6 mb-8">
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-6 shadow-md">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-semibold text-blue-600 uppercase">Jadwal Hari Ini</p>
          <p class="text-4xl font-bold text-blue-900 mt-2">{{ $todayLessons }}</p>
          <p class="text-sm text-blue-700 mt-1">jam pelajaran</p>
        </div>
        <div class="text-5xl">📅</div>
      </div>
    </div>

    <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-lg p-6 shadow-md">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-semibold text-green-600 uppercase">Trip Bulan Ini</p>
          <p class="text-4xl font-bold text-green-900 mt-2">{{ $thisMonthTrips }} / 12</p>
          <p class="text-sm text-green-700 mt-1">poin perbulan</p>
        </div>
        <div class="text-5xl">🚗</div>
      </div>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="grid md:grid-cols-2 gap-6">
    <a href="{{ route('lessons.teacher') }}" class="bg-white border border-gray-200 rounded-lg p-6 shadow-md hover:shadow-lg transition-all hover:scale-105 transform">
      <div class="flex items-start gap-4">
        <div class="text-4xl">📝</div>
        <div>
          <h3 class="text-lg font-bold text-gray-900 mb-1">Lihat Jadwal</h3>
          <p class="text-gray-600 text-sm">Absen kelas & kelola kehadiran siswa.</p>
        </div>
      </div>
    </a>

    <a href="{{ route('attendance.teacher') }}" class="bg-white border border-gray-200 rounded-lg p-6 shadow-md hover:shadow-lg transition-all hover:scale-105 transform">
      <div class="flex items-start gap-4">
        <div class="text-4xl">✓</div>
        <div>
          <h3 class="text-lg font-bold text-gray-900 mb-1">Absensi</h3>
          <p class="text-gray-600 text-sm">Rekap kehadiran & laporan siswa.</p>
        </div>
      </div>
    </a>
  </div>
</x-app-layout>
