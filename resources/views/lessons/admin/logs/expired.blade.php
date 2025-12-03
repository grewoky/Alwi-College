<x-admin-layout>
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-orange-50 to-slate-50">
  <!-- Header Section with Gradient -->
  <div class="bg-gradient-to-r from-orange-500 via-orange-600 to-red-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="flex items-start justify-between">
        <div class="space-y-3">
          <div class="flex items-center gap-3">
            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h1 class="text-4xl font-bold">Jadwal Akan Dihapus</h1>
          </div>
          <p class="text-orange-100 text-lg">Tinjau jadwal yang akan dihapus secara otomatis dalam pembersihan sistem berikutnya</p>
        </div>
        <div class="text-orange-100 text-sm font-semibold uppercase tracking-wider">Manajemen Jadwal</div>
      </div>
    </div>
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Status Alert -->
    @if($totalExpired > 0)
      <div class="mb-8 rounded-2xl border-2 border-orange-300 bg-gradient-to-br from-orange-50 to-orange-100 p-6 shadow-sm">
        <div class="flex gap-4">
          <div class="flex-shrink-0">
            <div class="flex items-center justify-center h-12 w-12 rounded-full bg-orange-200 animate-pulse">
              <svg class="h-6 w-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
              </svg>
            </div>
          </div>
          <div>
            <h3 class="text-sm font-bold text-orange-900">{{ $totalExpired }} jadwal siap dihapus</h3>
            <p class="mt-1 text-sm text-orange-800">Jadwal-jadwal ini akan dihapus secara otomatis dalam pembersihan sistem berikutnya pada pukul 00:30 hari ini.</p>
          </div>
        </div>
      </div>
    @else
      <div class="mb-8 rounded-2xl border-2 border-emerald-300 bg-gradient-to-br from-emerald-50 to-emerald-100 p-6 shadow-sm">
        <div class="flex gap-4">
          <div class="flex-shrink-0">
            <div class="flex items-center justify-center h-12 w-12 rounded-full bg-emerald-200">
              <svg class="h-6 w-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
              </svg>
            </div>
          </div>
          <div>
            <h3 class="text-sm font-bold text-emerald-900">Tidak ada jadwal yang akan dihapus</h3>
            <p class="mt-1 text-sm text-emerald-800">Semua jadwal saat ini aktif dan tidak memerlukan penghapusan.</p>
          </div>
        </div>
      </div>
    @endif

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100">
      @if($expiredLessons->count() > 0)
        <div class="overflow-x-auto">
          <table class="min-w-[900px] min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-orange-50 via-red-50 to-orange-50 border-b-2 border-orange-200">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Kelas</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Guru</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Materi</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Waktu</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Tindakan</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              @foreach($expiredLessons as $lesson)
                <tr class="hover:bg-orange-50 transition-colors duration-150">
                  <td class="px-6 py-4 text-sm font-medium text-gray-900">
                    {{ \Carbon\Carbon::parse($lesson->date)->format('d M Y') }}
                  </td>
                  <td class="px-6 py-4 text-sm">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gradient-to-r from-orange-100 to-orange-50 border border-orange-200">
                      <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                      </svg>
                      <span class="text-gray-900 font-medium">{{ $lesson->classRoom->name ?? '-' }}</span>
                    </span>
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                    {{ $lesson->teacher->user->name ?? '-' }}
                  </td>
                  <td class="px-6 py-4 text-sm">
                    <span class="inline-flex items-center px-3 py-1 rounded-lg bg-orange-100 text-orange-900 font-medium text-xs">
                      {{ $lesson->subject->name ?? 'Umum' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 text-sm">
                    <span class="inline-flex items-center gap-1 text-gray-900">
                      <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                      @if($lesson->start_time && $lesson->end_time)
                        {{ $lesson->start_time }} - {{ $lesson->end_time }}
                      @else
                        <span class="text-gray-400">-</span>
                      @endif
                    </span>
                  </td>
                  <td class="px-6 py-4 text-sm">
                    <form method="POST" action="{{ route('lessons.destroy', $lesson->id) }}" style="display:inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold text-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 group" onclick="return confirm('Hapus jadwal ini sekarang?')">
                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus
                      </button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        @if($expiredLessons->hasPages())
          <div class="bg-gradient-to-r from-slate-50 to-orange-50 px-6 py-4 border-t border-gray-200">
            {{ $expiredLessons->links() }}
          </div>
        @endif
      @else
        <div class="text-center py-16">
          <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/>
          </svg>
          <h3 class="mt-2 text-lg font-medium text-gray-900">Tidak ada jadwal yang akan dihapus</h3>
          <p class="mt-1 text-sm text-gray-500">Semua jadwal saat ini aktif dan tidak memenuhi kriteria penghapusan</p>
        </div>
      @endif
    </div>

    <!-- Info Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-12">
      <!-- Info Box 1: Automatic Cleanup -->
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
            <h3 class="text-base font-semibold text-gray-900">Operasi Otomatis</h3>
            <p class="mt-2 text-sm text-gray-600">
              Sistem menjalankan pembersihan terjadwal untuk menghapus jadwal yang sudah melewati tanggal berlakunya setiap hari secara otomatis.
            </p>
            <div class="mt-4 space-y-2">
              <div class="flex items-center gap-2 text-sm text-gray-700">
                <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                </svg>
                <span>Jadwal berlalu otomatis dihapus</span>
              </div>
              <div class="flex items-center gap-2 text-sm text-gray-700">
                <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                </svg>
                <span>Riwayat disimpan untuk audit</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Info Box 2: Cleanup Schedule -->
      <div class="group bg-white rounded-2xl p-6 shadow-md border border-red-100 hover:shadow-lg transition-all">
        <div class="flex items-start gap-4">
          <div class="flex-shrink-0">
            <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-br from-red-100 to-red-50">
              <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
          </div>
          <div class="flex-1">
            <h3 class="text-base font-semibold text-gray-900">Jadwal Penghapusan</h3>
            <p class="mt-2 text-sm text-gray-600">
              Pembersihan data dilaksanakan secara berkala dengan jadwal yang telah ditentukan untuk menjaga performa sistem.
            </p>
            <div class="mt-4 space-y-2 text-sm">
              <div class="flex items-center gap-2 text-gray-700">
                <span class="font-semibold">⏰ Waktu:</span>
                <span>Pukul 00:30 setiap hari</span>
              </div>
              <div class="flex items-center gap-2 text-gray-700">
                <span class="font-semibold">🎯 Target:</span>
                <span>Jadwal dengan tanggal < hari ini</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</x-admin-layout>
