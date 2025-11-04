<x-admin-layout>
<div class="min-h-screen bg-white">
  <!-- Header Section -->
  <div class="border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="space-y-2">
        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Manajemen Jadwal</p>
        <h1 class="text-4xl font-bold text-gray-900">Jadwal Akan Dihapus</h1>
        <p class="text-lg text-gray-600 mt-3">Tinjau jadwal yang akan dihapus secara otomatis dalam pembersihan sistem berikutnya</p>
      </div>
    </div>
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Status Alert -->
    @if($totalExpired > 0)
      <div class="mb-8 rounded-lg border border-yellow-200 bg-yellow-50 p-6">
        <div class="flex gap-4">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
            </svg>
          </div>
          <div>
            <h3 class="text-sm font-semibold text-yellow-900">{{ $totalExpired }} jadwal siap dihapus</h3>
            <p class="mt-1 text-sm text-yellow-800">Jadwal-jadwal ini akan dihapus secara otomatis dalam pembersihan sistem berikutnya pada pukul 00:30 hari ini.</p>
          </div>
        </div>
      </div>
    @else
      <div class="mb-8 rounded-lg border border-green-200 bg-green-50 p-6">
        <div class="flex gap-4">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
            </svg>
          </div>
          <div>
            <h3 class="text-sm font-semibold text-green-900">Tidak ada jadwal yang akan dihapus</h3>
            <p class="mt-1 text-sm text-green-800">Semua jadwal saat ini aktif dan tidak memerlukan penghapusan.</p>
          </div>
        </div>
      </div>
    @endif

    <!-- Table Section -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
      @if($expiredLessons->count() > 0)
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 border-b border-gray-200">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kelas</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Guru</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Materi</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Waktu</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tindakan</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              @foreach($expiredLessons as $lesson)
                <tr class="hover:bg-gray-50 transition-colors">
                  <td class="px-6 py-4 text-sm">
                    <div class="space-y-1">
                      <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($lesson->date)->format('d M Y') }}</p>
                      <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($lesson->date)->format('l') }}</p>
                    </div>
                  </td>
                  <td class="px-6 py-4 text-sm">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                      {{ $lesson->classRoom->name ?? '-' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-900">
                    {{ $lesson->teacher->user->name ?? '-' }}
                  </td>
                  <td class="px-6 py-4 text-sm">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                      {{ $lesson->subject->name ?? 'Umum' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 text-sm">
                    @if($lesson->start_time && $lesson->end_time)
                      <p class="text-gray-900">{{ $lesson->start_time }} - {{ $lesson->end_time }}</p>
                    @else
                      <p class="text-gray-400">-</p>
                    @endif
                  </td>
                  <td class="px-6 py-4 text-sm">
                    <form method="POST" action="{{ route('lessons.destroy', $lesson->id) }}" style="display:inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="text-red-600 hover:text-red-900 font-medium" onclick="return confirm('Hapus jadwal ini sekarang?')">
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
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
          {{ $expiredLessons->links() }}
        </div>
      @else
        <div class="px-6 py-12 text-center">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/>
          </svg>
          <p class="mt-4 text-lg font-medium text-gray-900">Tidak ada jadwal yang akan dihapus</p>
          <p class="mt-2 text-sm text-gray-600">Semua jadwal saat ini aktif dan tidak memenuhi kriteria penghapusan</p>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
            </div>
          </div>
          <div class="flex-1">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Operasi Penghapusan Otomatis</h3>
            <p class="text-sm text-gray-600 mb-4">Sistem menjalankan pembersihan terjadwal untuk menghapus jadwal yang sudah melewati tanggal berlakunya.</p>
            <ul class="space-y-2 text-sm text-gray-600">
              <li class="flex gap-2">
                <svg class="h-4 w-4 text-gray-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                </svg>
                <span>Jadwal dapat dibuat berbulan-bulan sebelumnya</span>
              </li>
              <li class="flex gap-2">
                <svg class="h-4 w-4 text-gray-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                </svg>
                <span>Penghapusan dilakukan secara otomatis setiap hari</span>
              </li>
              <li class="flex gap-2">
                <svg class="h-4 w-4 text-gray-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                </svg>
                <span>Semua data disimpan untuk keperluan audit</span>
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
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Jadwal Penghapusan Sistem</h3>
            <p class="text-sm text-gray-600 mb-4">Pembersihan data dilaksanakan secara berkala dengan jadwal yang telah ditentukan.</p>
            <div class="space-y-3">
              <div class="flex items-center gap-3 text-sm">
                <span class="font-medium text-gray-900 min-w-fit">Waktu:</span>
                <span class="text-gray-600">Pukul 00:30 setiap hari</span>
              </div>
              <div class="flex items-center gap-3 text-sm">
                <span class="font-medium text-gray-900 min-w-fit">Zona Waktu:</span>
                <span class="text-gray-600">Server (WIB)</span>
              </div>
              <div class="flex items-center gap-3 text-sm">
                <span class="font-medium text-gray-900 min-w-fit">Target:</span>
                <span class="text-gray-600">Jadwal dengan tanggal < hari ini</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-12 flex gap-4 flex-col sm:flex-row">
      <a href="{{ route('lessons.show-delete-log') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
        Lihat Log Penghapusan
      </a>
      <a href="{{ route('lessons.show-generate') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-gray-900 hover:bg-gray-800 transition-colors">
        Buat Jadwal Baru
      </a>
    </div>
  </div>
</div>
</x-admin-layout>
