<x-app-layout>
  <x-slot name="title">Guru • Dashboard</x-slot>

  <div class="grid md:grid-cols-2 gap-4">
    <x-stat-card label="Jadwal Hari Ini" :value="$todayLessons" />
    <x-stat-card label="Trip Bulan Ini" :value="$thisMonthTrips" />
  </div>

  <div class="mt-6 grid md:grid-cols-2 gap-4">
    <a href="{{ route('teacher.lessons') }}" class="bg-white border rounded-2xl p-4 shadow-sm hover:shadow-md transition">
      <div class="text-sm font-semibold mb-1">Lihat Jadwal</div>
      <div class="text-gray-500 text-sm">Absen kelas & kelola kehadiran.</div>
    </a>
    <a href="{{ route('info.list') }}" class="bg-white border rounded-2xl p-4 shadow-sm hover:shadow-md transition">
      <div class="text-sm font-semibold mb-1">Info Siswa</div>
      <div class="text-gray-500 text-sm">Akses & unduh berkas dari siswa.</div>
    </a>
  </div>
</x-app-layout>
