<x-app-layout>
  <x-slot name="title">Guru • Dashboard</x-slot>

  <style>
    .card-hover{transition:all .25s cubic-bezier(0.4,0,0.2,1)}
    .card-hover:hover{transform:translateY(-4px);box-shadow:0 12px 24px rgba(0,0,0,0.08)}
    .heading-inline{position:relative;display:inline-block}
    .heading-inline::after{content:"";position:absolute;left:0;bottom:-10px;width:100%;height:4px;border-radius:9999px;background:linear-gradient(90deg,#3B63B5 0%, #6FA2FF 50%, #3B63B5 100%);opacity:.25}
  </style>
  <!-- Hero Header Section -->
  <div class="mb-8 bg-white border border-slate-200 rounded-2xl p-8 shadow-sm">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
      <div>
        <p class="text-sm font-semibold text-indigo-600 uppercase tracking-wide">Selamat Datang</p>
        <h1 class="text-4xl font-bold text-slate-900 mt-2"><span class="heading-inline">Dashboard Guru</span></h1>
        <p class="text-slate-500 mt-3 max-w-2xl">Kelola jadwal mengajar, absensi kelas, dan kebutuhan trip dalam satu tempat. Gunakan akses cepat di bawah untuk memulai aktivitas harian Anda.</p>
      </div>
      <div class="h-20 w-20 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-4xl">
        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 14l9-5-9-5-9 5 9 5z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 14v7" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M5 12.5V17a2 2 0 002 2h10a2 2 0 002-2v-4.5" />
        </svg>
      </div>
    </div>
  </div>

  <!-- Key Statistics -->
  <div class="grid md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm hover:shadow-md transition card-hover">
      <div class="flex items-start justify-between">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Jadwal Hari Ini</p>
          <p class="mt-3 text-3xl font-bold text-slate-900">{{ $todayLessons }}</p>
          <p class="mt-2 text-sm text-slate-500">Jam pelajaran yang Anda ajar</p>
        </div>
        <div class="h-12 w-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 7V3m8 4V3M4 11h16M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
          </svg>
        </div>
      </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm hover:shadow-md transition card-hover">
      <div class="flex items-start justify-between">
        <div>
          <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Trip Bulan Ini</p>
          <p class="mt-3 text-3xl font-bold text-slate-900">{{ $thisMonthTrips }} <span class="text-base font-medium text-slate-400">/ 90</span></p>
          <p class="mt-2 text-sm text-slate-500">Poin</p>
        </div>
        <div class="h-12 w-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M5 16l1.5-4.5A3 3 0 019.356 9h5.288a3 3 0 012.856 2.5L19 16m-14 0h14m-14 0v2a1 1 0 001 1h1m12-3v2a1 1 0 01-1 1h-1m-10 0h6m-6 0a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 104 0 2 2 0 00-4 0z" />
          </svg>
        </div>
      </div>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="mb-12">
    <h2 class="text-2xl font-bold text-gray-900 mb-6"><span class="heading-inline">Akses Cepat</span></h2>

    @php
      $teacherLinks = [
        [
          'label' => 'Lihat Jadwal',
          'description' => 'Kelola agenda mengajar dan materi kelas',
          'route' => route('lessons.teacher')
        ],
        [
          'label' => 'Absensi',
          'description' => 'Rekap dan konfirmasi kehadiran siswa',
          'route' => route('attendance.teacher')
        ],
        [
          'label' => 'Dokumen Siswa',
          'description' => 'Review file yang diunggah siswa',
          'route' => route('info.teacher.student-files')
        ],
      ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      @foreach ($teacherLinks as $link)
        <a href="{{ $link['route'] }}" class="group bg-white border border-slate-200 rounded-xl p-6 shadow-sm hover:shadow-md transition flex flex-col card-hover focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-300">
          <div class="flex items-center justify-between mb-2">
            <h3 class="text-base font-semibold text-slate-900">{{ $link['label'] }}</h3>
            <svg class="w-5 h-5 text-slate-300 group-hover:text-emerald-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 5l7 7-7 7" />
            </svg>
          </div>
          <p class="text-sm text-slate-500">{{ $link['description'] }}</p>
        </a>
      @endforeach
    </div>
  </div>
</x-app-layout>
