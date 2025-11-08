<x-admin-layout>
<div class="min-h-screen bg-gray-50 py-12">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Back Link -->
    <div class="mb-6">
      <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-200 bg-white text-slate-700 font-medium shadow-sm">
        <span class="text-lg">←</span>
        <span>Kembali ke Dashboard Admin</span>
      </a>
    </div>
    
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-4xl font-bold text-gray-900 mb-2">Dashboard Jadwal Pelajaran</h1>
      <p class="text-gray-600">Lihat statistik ringkas dan kelola jadwal yang sudah terunggah</p>
    </div>

    <!-- Quick Actions -->
    <div class="mb-8 flex flex-wrap gap-3">
      <a href="{{ route('lessons.generate.form') }}" class="inline-flex items-center gap-3 px-5 py-3 rounded-lg bg-blue-600 text-white font-medium shadow-sm">
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/20">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 4v16m8-8H4" />
          </svg>
        </span>
        <span>Generate Jadwal Baru</span>
      </a>
      <a href="{{ route('lessons.admin') }}" class="inline-flex items-center gap-3 px-5 py-3 rounded-lg bg-white text-slate-900 font-medium border border-slate-200 shadow-sm">
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-indigo-100 text-indigo-600">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 7V3m8 4V3M4 11h16m-1 8H5a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2z" />
          </svg>
        </span>
        <span>Lihat Semua Jadwal</span>
      </a>
    </div>

    <!-- Statistics Cards -->
    @php
      $statCards = [
        [
          'label' => 'Total Jadwal',
          'value' => $totalLessons,
          'description' => 'Semua jadwal aktif',
          'icon' => 'calendar'
        ],
        [
          'label' => 'Guru Aktif',
          'value' => $totalTeachers,
          'description' => 'Pengajar yang terjadwal',
          'icon' => 'academic-cap'
        ],
        [
          'label' => 'Kelas Terjadwal',
          'value' => $totalClasses,
          'description' => 'Total kelas memiliki jadwal',
          'icon' => 'building'
        ],
        [
          'label' => 'Belum Ada Jam',
          'value' => $lessonsWithoutTime,
          'description' => 'Perlu dilengkapi',
          'icon' => 'alert'
        ],
      ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
      @foreach ($statCards as $card)
        <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm hover:shadow-md transition">
          <div class="flex items-start justify-between">
            <div>
              <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">{{ $card['label'] }}</p>
              <p class="mt-3 text-3xl font-bold text-slate-900">{{ $card['value'] }}</p>
              <p class="mt-2 text-sm text-slate-500">{{ $card['description'] }}</p>
            </div>
            <div class="h-12 w-12 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center">
              @switch($card['icon'])
                @case('calendar')
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 7V3m8 4V3M4 11h16M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                  </svg>
                  @break
                @case('academic-cap')
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 14v7" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M5 12.5V17a2 2 0 002 2h10a2 2 0 002-2v-4.5" />
                  </svg>
                  @break
                @case('building')
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M3 21h18M5 21V7a2 2 0 012-2h10a2 2 0 012 2v14M9 21v-4h6v4" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 11h.01M15 11h.01M12 11h.01M9 15h.01M15 15h.01M12 15h.01" />
                  </svg>
                  @break
                @case('alert')
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a1.5 1.5 0 001.29 2.25h17.78A1.5 1.5 0 0022.18 18L13.71 3.86a1.5 1.5 0 00-2.42 0z" />
                  </svg>
                  @break
              @endswitch
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <!-- Recent Lessons Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
      <!-- Jadwal Terbaru -->
      <div class="lg:col-span-2">
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
          <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
            <div class="flex items-center gap-3">
              <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-indigo-100 text-indigo-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 7V3m8 4V3M4 11h16m-1 8H5a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2z" />
                </svg>
              </span>
              <div>
                <h2 class="text-base font-semibold text-slate-900">Jadwal Terbaru Ditambahkan</h2>
                <p class="text-sm text-slate-500">Update jadwal terbaru yang masuk</p>
              </div>
            </div>
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
                          <span class="text-slate-400 italic">Belum diisi</span>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="px-6 py-8 text-center text-gray-500">
              <p class="text-lg">Belum ada jadwal terbaru</p>
            </div>
          @endif
        </div>
      </div>

      <!-- Top Teachers -->
      <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-200">
          <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-indigo-100 text-indigo-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M17 20v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 9a4 4 0 110-8 4 4 0 010 8z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M23 20v-2a4 4 0 00-3-3.87" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M16 3.13a4 4 0 010 7.75" />
            </svg>
          </span>
          <div>
            <h2 class="text-base font-semibold text-slate-900">Guru Terbanyak</h2>
            <p class="text-sm text-slate-500">Jumlah jadwal terbanyak per guru</p>
          </div>
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
                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-indigo-50 text-indigo-600 text-sm font-semibold">
                      {{ $teacher->lessons_count }}
                    </span>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <div class="px-6 py-8 text-center text-gray-500">
            <p>Belum ada data guru</p>
          </div>
        @endif
      </div>
    </div>

  </div>
</div>
</x-admin-layout>
