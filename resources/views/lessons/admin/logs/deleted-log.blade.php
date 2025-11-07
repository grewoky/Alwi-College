<x-admin-layout>
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-50">
  <!-- Header Section with Gradient -->
  <div class="bg-gradient-to-r from-blue-500 via-blue-600 to-cyan-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="flex items-start justify-between">
        <div class="space-y-3">
          <div class="flex items-center gap-3">
            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h1 class="text-4xl font-bold">Riwayat Penghapusan Jadwal</h1>
          </div>
          <p class="text-blue-100 text-lg">Pantau dan kelola semua jadwal yang telah dihapus dari sistem</p>
        </div>
        <div class="text-blue-100 text-sm font-semibold uppercase tracking-wider">Manajemen Data</div>
      </div>
    </div>
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
      <!-- Stat Card 1: Total Deleted -->
      <div class="group bg-white rounded-2xl p-6 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-blue-100">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Dihapus</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalDeleted }}</p>
            <p class="mt-1 text-xs text-gray-500">jadwal terhapus</p>
          </div>
          <div class="flex-shrink-0">
            <div class="flex items-center justify-center h-14 w-14 rounded-xl bg-gradient-to-br from-blue-100 to-blue-50 group-hover:scale-110 transition-transform duration-300">
              <svg class="h-7 w-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Stat Card 2: Automatic Deletion -->
      <div class="group bg-white rounded-2xl p-6 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-emerald-100">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Otomatis</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">
              @php
                $autoDeleted = DB::table('deleted_lessons_log')
                  ->where('deleted_by', 'system')
                  ->count();
              @endphp
              {{ $autoDeleted }}
            </p>
            <p class="mt-1 text-xs text-gray-500">oleh sistem</p>
          </div>
          <div class="flex-shrink-0">
            <div class="flex items-center justify-center h-14 w-14 rounded-xl bg-gradient-to-br from-emerald-100 to-emerald-50 group-hover:scale-110 transition-transform duration-300">
              <svg class="h-7 w-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Stat Card 3: Manual Deletion -->
      <div class="group bg-white rounded-2xl p-6 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-amber-100">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Manual</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">
              @php
                $manualDeleted = DB::table('deleted_lessons_log')
                  ->where('deleted_by', '!=', 'system')
                  ->count();
              @endphp
              {{ $manualDeleted }}
            </p>
            <p class="mt-1 text-xs text-gray-500">oleh pengguna</p>
          </div>
          <div class="flex-shrink-0">
            <div class="flex items-center justify-center h-14 w-14 rounded-xl bg-gradient-to-br from-amber-100 to-amber-50 group-hover:scale-110 transition-transform duration-300">
              <svg class="h-7 w-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m0 0v2m0-6h2m-2 0h-2m8.5 0h-2m2 0h2m0 4a2 2 0 100 4m0-4a2 2 0 110 4m0 0v2m0-6h2m-2 0h-2"/>
              </svg>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100">
      @if($deletedLog->count() > 0)
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-blue-50 via-cyan-50 to-blue-50 border-b-2 border-blue-200">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Kelas</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Guru</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Waktu</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Dihapus</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Tipe</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              @foreach($deletedLog as $log)
                <tr class="hover:bg-blue-50 transition-colors duration-150">
                  <td class="px-6 py-4 text-sm font-medium text-gray-900">
                    {{ \Carbon\Carbon::parse($log->lesson_date)->format('d M Y') }}
                  </td>
                  <td class="px-6 py-4 text-sm">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gradient-to-r from-blue-100 to-blue-50 border border-blue-200">
                      <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                      </svg>
                      <span class="text-gray-900 font-medium">{{ $log->classroom_id }}</span>
                    </span>
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-700">
                    {{ optional($log)->teacher_id ? 'ID: ' . $log->teacher_id : '-' }}
                  </td>
                  <td class="px-6 py-4 text-sm">
                    <span class="inline-flex items-center gap-1 text-gray-900">
                      <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                      {{ date('H:i', strtotime($log->start_time)) }} - {{ date('H:i', strtotime($log->end_time)) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-700">
                    {{ \Carbon\Carbon::parse($log->deleted_at)->format('d M Y H:i') }}
                  </td>
                  <td class="px-6 py-4 text-sm">
                    @if($log->deleted_by === 'system')
                      <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gradient-to-r from-emerald-100 to-emerald-50 border border-emerald-200 text-emerald-900 font-medium text-xs uppercase">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                        </svg>
                        Otomatis
                      </span>
                    @else
                      <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gradient-to-r from-orange-100 to-orange-50 border border-orange-200 text-orange-900 font-medium text-xs uppercase">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.343a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM15.657 14.657a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM11 17a1 1 0 102 0v-1a1 1 0 10-2 0v1zM5.343 15.657a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414l-.707.707zM4 11a1 1 0 01-1-1 1 1 0 110-2h1a1 1 0 011 1 1 1 0 01-1 1zM5.343 5.343a1 1 0 011.414-1.414l-.707-.707a1 1 0 00-1.414 1.414l.707.707z"/>
                        </svg>
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
        @if($deletedLog->hasPages())
          <div class="bg-gradient-to-r from-slate-50 to-blue-50 px-6 py-4 border-t border-gray-200">
            {{ $deletedLog->links() }}
          </div>
        @endif
      @else
        <div class="text-center py-16">
          <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
          <h3 class="mt-2 text-lg font-medium text-gray-900">Tidak ada data</h3>
          <p class="mt-1 text-sm text-gray-500">Belum ada jadwal yang dihapus</p>
        </div>
      @endif
    </div>

    <!-- Info Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-12">
      <!-- Info Box 1: Automatic Deletion -->
      <div class="group bg-white rounded-2xl p-6 shadow-md border border-emerald-100 hover:shadow-lg transition-all">
        <div class="flex items-start gap-4">
          <div class="flex-shrink-0">
            <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-br from-emerald-100 to-emerald-50">
              <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
            </div>
          </div>
          <div class="flex-1">
            <h3 class="text-base font-semibold text-gray-900">Penghapusan Otomatis</h3>
            <p class="mt-2 text-sm text-gray-600">
              Sistem secara otomatis menghapus jadwal yang sudah lewat tanggal setiap hari pada pukul 00:30 untuk menjaga data tetap relevan.
            </p>
            <div class="mt-4 space-y-2">
              <div class="flex items-center gap-2 text-sm text-gray-700">
                <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                </svg>
                <span>Jadwal yang sudah terlewat otomatis dihapus</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Info Box 2: Manual Deletion -->
      <div class="group bg-white rounded-2xl p-6 shadow-md border border-orange-100 hover:shadow-lg transition-all">
        <div class="flex items-start gap-4">
          <div class="flex-shrink-0">
            <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-br from-orange-100 to-orange-50">
              <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m0 0v2m0-6h2m-2 0h-2m8.5 0h-2m2 0h2m0 4a2 2 0 100 4m0-4a2 2 0 110 4m0 0v2m0-6h2m-2 0h-2"/>
              </svg>
            </div>
          </div>
          <div class="flex-1">
            <h3 class="text-base font-semibold text-gray-900">Penghapusan Manual</h3>
            <p class="mt-2 text-sm text-gray-600">
              Admin atau guru dapat menghapus jadwal secara manual melalui halaman jadwal akan dihapus kapan saja sesuai kebutuhan.
            </p>
            <div class="mt-4 space-y-2">
              <div class="flex items-center gap-2 text-sm text-gray-700">
                <svg class="w-4 h-4 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                </svg>
                <span>Dapat dihapus secara manual kapan saja</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</x-admin-layout>
