<x-admin-layout title="Admin • Dashboard">
  <!-- Statistics Cards -->
  <div class="grid md:grid-cols-4 gap-4">
    <x-stat-card label="Siswa" :value="$stats['students']" />
    <x-stat-card label="Guru" :value="$stats['teachers']" />
    <x-stat-card label="Kelas" :value="$stats['classes']" />
    <x-stat-card label="Pembayaran Pending" :value="$stats['payments_pending']" />
  </div>

  <!-- Quick Actions - Grid -->
  <div class="mt-8 grid md:grid-cols-4 gap-6">
    <!-- View Jadwal -->
    <a href="{{ route('lessons.admin.dashboard') }}" class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-6 shadow-md hover:shadow-lg transition-all hover:scale-105 transform">
      <div class="flex items-start gap-4">
        <div class="text-4xl">📚</div>
        <div>
          <h3 class="text-lg font-bold text-blue-900 mb-2">Jadwal Pelajaran</h3>
          <p class="text-sm text-blue-700">Dashboard & kelola jadwal les.</p>
        </div>
      </div>
    </a>

    <!-- Generate Jadwal -->
    <a href="{{ route('lessons.generate.form') }}" class="bg-gradient-to-br from-cyan-50 to-cyan-100 border border-cyan-200 rounded-lg p-6 shadow-md hover:shadow-lg transition-all hover:scale-105 transform">
      <div class="flex items-start gap-4">
        <div class="text-4xl">📅</div>
        <div>
          <h3 class="text-lg font-bold text-cyan-900 mb-2">Generate Jadwal</h3>
          <p class="text-sm text-cyan-700">Buat jadwal baru setiap hari.</p>
        </div>
      </div>
    </a>

    <!-- Info Management -->
    <a href="{{ route('info.admin.list') }}" class="bg-gradient-to-br from-indigo-50 to-indigo-100 border border-indigo-200 rounded-lg p-6 shadow-md hover:shadow-lg transition-all hover:scale-105 transform">
      <div class="flex items-start gap-4">
        <div class="text-4xl">📋</div>
        <div>
          <h3 class="text-lg font-bold text-indigo-900 mb-2">Info File</h3>
          <p class="text-sm text-indigo-700">Kelola & download file siswa.</p>
        </div>
      </div>
    </a>

    <!-- Trip Management -->
    <a href="{{ route('trips.index') }}" class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-lg p-6 shadow-md hover:shadow-lg transition-all hover:scale-105 transform">
      <div class="flex items-start gap-4">
        <div class="text-4xl">🚗</div>
        <div>
          <h3 class="text-lg font-bold text-green-900 mb-2">Rekap Trip Guru</h3>
          <p class="text-sm text-green-700">Kelola 90 poin/bulan & hitung sisa.</p>
        </div>
      </div>
    </a>

    <!-- Payment Verification -->
    <a href="{{ route('pay.list') }}" class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-lg p-6 shadow-md hover:shadow-lg transition-all hover:scale-105 transform">
      <div class="flex items-start gap-4">
        <div class="text-4xl">💳</div>
        <div>
          <h3 class="text-lg font-bold text-purple-900 mb-2">Verifikasi Pembayaran</h3>
          <p class="text-sm text-purple-700">Approve/Reject bukti pembayaran.</p>
        </div>
      </div>
    </a>
  </div>
</x-admin-layout>
