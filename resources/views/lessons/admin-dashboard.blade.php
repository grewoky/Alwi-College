<x-admin-layout>
<div class="min-h-screen bg-gray-50 py-12">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-4xl font-bold text-gray-900 mb-2">📚 Dashboard Jadwal Pelajaran</h1>
      <p class="text-gray-600">Lihat statistik dan kelola jadwal yang telah di-upload</p>
    </div>

    <!-- Quick Actions -->
    <div class="mb-8 flex flex-wrap gap-3">
      <a href="{{ route('lessons.generate.form') }}" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-bold transition">
        ➕ Generate Jadwal Baru
      </a>
      <a href="{{ route('lessons.admin') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold transition">
        📋 Lihat Semua Jadwal
      </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <!-- Total Jadwal -->
      <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-600">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-600 text-sm font-semibold">Total Jadwal</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalLessons }}</p>
          </div>
          <div class="text-4xl text-blue-600">📅</div>
        </div>
      </div>

      <!-- Guru -->
      <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-600">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-600 text-sm font-semibold">Guru Aktif</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalTeachers }}</p>
          </div>
          <div class="text-4xl text-purple-600">👨‍🏫</div>
        </div>
      </div>

      <!-- Kelas -->
      <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-600">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-600 text-sm font-semibold">Kelas Terjadwal</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalClasses }}</p>
          </div>
          <div class="text-4xl text-green-600">🏫</div>
        </div>
      </div>

      <!-- Belum Lengkap -->
      <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-600">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-600 text-sm font-semibold">Belum Ada Jam</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $lessonsWithoutTime }}</p>
            <p class="text-xs text-yellow-600 mt-1">Perlu dilengkapi</p>
          </div>
          <div class="text-4xl text-yellow-600">⚠️</div>
        </div>
      </div>
    </div>

    <!-- Recent Lessons Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
      <!-- Jadwal Terbaru -->
      <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
          <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-800 text-white">
            <h2 class="text-xl font-bold">📝 Jadwal Terbaru Ditambahkan</h2>
          </div>
          
          @if($recentLessons->count())
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead class="bg-gray-100 border-b">
                  <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Kelas</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Guru</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Jam</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($recentLessons as $lesson)
                    <tr class="border-b hover:bg-gray-50 transition">
                      <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                        {{ \Carbon\Carbon::parse($lesson->date)->format('d M Y') }}
                      </td>
                      <td class="px-6 py-4 text-sm text-gray-700">
                        {{ $lesson->classRoom->name }}
                      </td>
                      <td class="px-6 py-4 text-sm text-gray-700">
                        {{ $lesson->teacher->user->name }}
                      </td>
                      <td class="px-6 py-4 text-sm text-gray-700">
                        @if($lesson->start_time && $lesson->end_time)
                          {{ $lesson->start_time }} - {{ $lesson->end_time }}
                        @else
                          <span class="text-yellow-600 font-semibold">⚠️ Belum</span>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="px-6 py-8 text-center text-gray-500">
              <p class="text-lg">📭 Belum ada jadwal</p>
            </div>
          @endif
        </div>
      </div>

      <!-- Top Teachers -->
      <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-purple-800 text-white">
          <h2 class="text-lg font-bold">👨‍🏫 Guru Terbanyak</h2>
        </div>
        
        @if($teachersLessonCount->count())
          <div class="divide-y">
            @foreach($teachersLessonCount->take(10) as $teacher)
              <div class="px-6 py-4 hover:bg-gray-50 transition">
                <div class="flex items-center justify-between">
                  <div class="flex-1">
                    <p class="font-semibold text-gray-900">{{ $teacher->user->name }}</p>
                    <p class="text-sm text-gray-600">{{ $teacher->lessons_count }} jadwal</p>
                  </div>
                  <div class="text-right">
                    <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-bold">
                      {{ $teacher->lessons_count }}
                    </span>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <div class="px-6 py-8 text-center text-gray-500">
            <p>Belum ada guru</p>
          </div>
        @endif
      </div>
    </div>

    <!-- Full List with Filters -->
    <div class="bg-white rounded-lg shadow-lg">
      <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-green-800 text-white">
        <h2 class="text-xl font-bold">📋 Daftar Lengkap Jadwal</h2>
      </div>
      
      <div class="p-6">
        <a href="{{ route('lessons.admin') }}" class="inline-block px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-bold transition">
          → Lihat Jadwal Lengkap dengan Filter
        </a>
      </div>
    </div>

  </div>
</div>
</x-admin-layout>
