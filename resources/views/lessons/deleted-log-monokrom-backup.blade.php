<x-admin-layout>
<div class="min-h-screen bg-white">
  <!-- Header Section -->
  <div class="border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="space-y-2">
        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Manajemen Data</p>
        <h1 class="text-4xl font-bold text-gray-900">Riwayat Penghapusan Jadwal</h1>
        <p class="text-lg text-gray-600 mt-3">Pantau dan kelola semua jadwal yang telah dihapus dari sistem</p>
      </div>
    </div>
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
      <!-- Stat Card 1: Total Deleted -->
      <div class="bg-white border border-gray-200 rounded-lg p-8 hover:border-gray-300 transition-all">
        <div class="flex items-baseline justify-between">
          <div>
            <p class="text-sm font-semibold text-gray-600 uppercase">Total Dihapus</p>
            <p class="mt-2 text-4xl font-bold text-gray-900">{{ $totalDeleted }}</p>
          </div>
          <div class="flex-shrink-0">
            <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-gray-100">
              <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3v-7"/>
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Stat Card 2: Automatic Deletion -->
      <div class="bg-white border border-gray-200 rounded-lg p-8 hover:border-gray-300 transition-all">
        <div class="flex items-baseline justify-between">
          <div>
            <p class="text-sm font-semibold text-gray-600 uppercase">Penghapusan Otomatis</p>
            <p class="mt-2 text-4xl font-bold text-gray-900">
              @php
                $autoDeleted = DB::table('deleted_lessons_log')
                  ->where('deleted_by', 'system')
                  ->count();
              @endphp
              {{ $autoDeleted }}
            </p>
          </div>
          <div class="flex-shrink-0">
            <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-blue-50">
              <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Stat Card 3: Manual Deletion -->
      <div class="bg-white border border-gray-200 rounded-lg p-8 hover:border-gray-300 transition-all">
        <div class="flex items-baseline justify-between">
          <div>
            <p class="text-sm font-semibold text-gray-600 uppercase">Penghapusan Manual</p>
            <p class="mt-2 text-4xl font-bold text-gray-900">
              @php
                $manualDeleted = DB::table('deleted_lessons_log')
                  ->where('deleted_by', '!=', 'system')
                  ->count();
              @endphp
              {{ $manualDeleted }}
            </p>
          </div>
          <div class="flex-shrink-0">
            <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-amber-50">
              <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m0 0v2m0-6h2m-2 0h-2m8.5 0h-2m2 0h2m0 4a2 2 0 100 4m0-4a2 2 0 110 4m0 0v2m0-6h2m-2 0h-2"/>
              </svg>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
      @if($deletedLog->count() > 0)
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 border-b border-gray-200">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal Jadwal</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kelas</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Waktu</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Dihapus Pada</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tipe Penghapusan</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              @foreach($deletedLog as $log)
                <tr class="hover:bg-gray-50 transition-colors">
                  <td class="px-6 py-4 text-sm">
                    <div class="space-y-1">
                      <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($log->lesson_date)->format('d M Y') }}</p>
                      <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($log->lesson_date)->format('l') }}</p>
                    </div>
                  </td>
                  <td class="px-6 py-4 text-sm">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                      {{ $log->classroom_id ?? '-' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 text-sm">
                    @if($log->start_time && $log->end_time)
                      <p class="text-gray-900">{{ $log->start_time }} - {{ $log->end_time }}</p>
                    @else
                      <p class="text-gray-400">-</p>
                    @endif
                  </td>
                  <td class="px-6 py-4 text-sm">
                    <div class="space-y-1">
                      <p class="text-gray-900">{{ \Carbon\Carbon::parse($log->deleted_at)->format('d M Y') }}</p>
                      <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($log->deleted_at)->format('H:i') }}</p>
                    </div>
                  </td>
                  <td class="px-6 py-4 text-sm">
                    @if($log->deleted_by === 'system')
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                        Otomatis
                      </span>
                    @else
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                        Manual
                      </span>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
          {{ $deletedLog->links() }}
        </div>
      @else
        <div class="px-6 py-12 text-center">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
          <p class="mt-4 text-lg font-medium text-gray-900">Belum ada data penghapusan</p>
          <p class="mt-2 text-sm text-gray-600">Jadwal yang dihapus akan muncul di sini</p>
        </div>
      @endif
    </div>

    <!-- Information Section -->
    <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-8">
      <!-- Info Card 1 -->
      <div class="border border-gray-200 rounded-lg p-8">
        <div class="flex items-start gap-4">
          <div class="flex-shrink-0">
            <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-gray-100">
              <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
            </div>
          </div>
          <div class="flex-1">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Pelacakan Data Lengkap</h3>
            <p class="text-sm text-gray-600 mb-4">Setiap jadwal yang dihapus direkam sepenuhnya untuk keperluan audit dan keamanan data.</p>
            <ul class="space-y-2 text-sm text-gray-600">
              <li class="flex gap-2">
                <svg class="h-4 w-4 text-gray-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                </svg>
                <span>Tanggal dan waktu penghapusan</span>
              </li>
              <li class="flex gap-2">
                <svg class="h-4 w-4 text-gray-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                </svg>
                <span>Identitas pengguna yang menghapus</span>
              </li>
              <li class="flex gap-2">
                <svg class="h-4 w-4 text-gray-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                </svg>
                <span>Riwayat lengkap setiap operasi</span>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Info Card 2 -->
      <div class="border border-gray-200 rounded-lg p-8">
        <div class="flex items-start gap-4">
          <div class="flex-shrink-0">
            <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-gray-100">
              <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
          </div>
          <div class="flex-1">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Siklus Penghapusan</h3>
            <p class="text-sm text-gray-600 mb-4">Sistem berjalan sesuai jadwal yang telah ditetapkan untuk menjaga konsistensi dan keandalan.</p>
            <div class="space-y-3">
              <div class="flex items-center gap-3 text-sm">
                <span class="font-medium text-gray-900 min-w-fit">Waktu Eksekusi:</span>
                <span class="text-gray-600">Pukul 00:30 setiap hari</span>
              </div>
              <div class="flex items-center gap-3 text-sm">
                <span class="font-medium text-gray-900 min-w-fit">Frekuensi:</span>
                <span class="text-gray-600">Sekali per 24 jam</span>
              </div>
              <div class="flex items-center gap-3 text-sm">
                <span class="font-medium text-gray-900 min-w-fit">Kriteria:</span>
                <span class="text-gray-600">Jadwal dengan tanggal lewat</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-12 flex gap-4 flex-col sm:flex-row">
      <a href="{{ route('lessons.show-expired') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
        Lihat Jadwal Akan Dihapus
      </a>
      <a href="{{ route('lessons.show-generate') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-gray-900 hover:bg-gray-800 transition-colors">
        Buat Jadwal Baru
      </a>
    </div>
  </div>
</div>
</x-admin-layout>
