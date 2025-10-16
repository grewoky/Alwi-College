<x-app-layout>
  <x-slot name="title">Admin • Dashboard</x-slot>

  <div class="grid md:grid-cols-4 gap-4">
    <x-stat-card label="Siswa" :value="$stats['students']" />
    <x-stat-card label="Guru" :value="$stats['teachers']" />
    <x-stat-card label="Kelas" :value="$stats['classes']" />
    <x-stat-card label="Pembayaran Pending" :value="$stats['payments_pending']" />
  </div>

  <div class="mt-6 grid md:grid-cols-3 gap-4">
    <a href="{{ route('lessons.generate.form') }}" class="bg-white border rounded-2xl p-4 shadow-sm hover:shadow-md transition">
      <div class="text-sm font-semibold mb-1">Generate Jadwal</div>
      <div class="text-gray-500 text-sm">Buat jadwal per 2 hari untuk kelas & guru.</div>
    </a>
    <a href="{{ route('trips.index') }}" class="bg-white border rounded-2xl p-4 shadow-sm hover:shadow-md transition">
      <div class="text-sm font-semibold mb-1">Rekap Trip</div>
      <div class="text-gray-500 text-sm">Lihat progres target 90 trip guru.</div>
    </a>
    <a href="{{ route('pay.list') }}" class="bg-white border rounded-2xl p-4 shadow-sm hover:shadow-md transition">
      <div class="text-sm font-semibold mb-1">Verifikasi Pembayaran</div>
      <div class="text-gray-500 text-sm">Approve/Reject bukti pembayaran siswa.</div>
    </a>
  </div>
</x-app-layout>
