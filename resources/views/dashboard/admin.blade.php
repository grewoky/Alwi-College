<x-admin-layout title="Admin • Dashboard">
  <!-- Hero Header Section -->
  <div class="mb-8 bg-gradient-to-r from-indigo-600 via-indigo-700 to-purple-700 rounded-2xl p-8 text-white shadow-lg">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-4xl font-bold mb-2"><span class="heading-inline">Welcome Admin</span></h1>
        <p class="text-indigo-100 text-lg">Kelola sistem pembelajaran Alwi College dengan mudah</p>
      </div>
      <div class="text-6xl hidden md:block">🎓</div>
    </div>
  </div>

  <!-- Statistics Cards -->
  <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    @php
      $statCards = [
        [
          'label' => 'Total Siswa',
          'value' => $stats['students'],
          'description' => 'Siswa aktif',
          'icon' => 'users'
        ],
        [
          'label' => 'Total Guru',
          'value' => $stats['teachers'],
          'description' => 'Pengajar aktif',
          'icon' => 'academic-cap'
        ],
        [
          'label' => 'Jadwal Hari Ini',
          'value' => $stats['today_lessons'],
          'description' => 'Kelas berlangsung',
          'icon' => 'calendar'
        ],
        [
          'label' => 'Pembayaran Pending',
          'value' => $stats['payments_pending'],
          'description' => 'Menunggu verifikasi',
          'icon' => 'credit-card'
        ],
      ];
    @endphp

    @foreach ($statCards as $card)
      <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm hover:shadow-md transition focus-within:shadow-md">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">{{ $card['label'] }}</p>
            <p class="mt-3 text-3xl font-bold text-slate-900">{{ $card['value'] }}</p>
            <p class="mt-2 text-sm text-slate-500">{{ $card['description'] }}</p>
          </div>
          <div class="h-12 w-12 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center">
            @switch($card['icon'])
              @case('users')
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M17 20v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 9a4 4 0 110-8 4 4 0 010 8z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M23 20v-2a4 4 0 00-3-3.87" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M16 3.13a4 4 0 010 7.75" />
                </svg>
                @break
              @case('academic-cap')
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 14l9-5-9-5-9 5 9 5z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 14v7" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M5 12.5V17a2 2 0 002 2h10a2 2 0 002-2v-4.5" />
                </svg>
                @break
              @case('calendar')
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 7V3m8 4V3M4 11h16M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                </svg>
                @break
              @case('credit-card')
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M2.25 7.5h19.5M3.75 6A1.5 1.5 0 015.25 4.5h13.5A1.5 1.5 0 0120.25 6v12a1.5 1.5 0 01-1.5 1.5H5.25A1.5 1.5 0 013.75 18V6z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M6 15h2m3 0h7" />
                </svg>
                @break
            @endswitch
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <!-- Quick Actions - Grid -->
  <div class="mb-12">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Akses Cepat</h2>
    
    @php
      $quickLinks = [
        [
          'label' => 'Jadwal Pelajaran',
          'description' => 'Lihat & kelola jadwal les',
          'route' => route('lessons.admin.dashboard')
        ],
        [
          'label' => 'Generate Jadwal',
          'description' => 'Buat jadwal baru otomatis',
          'route' => route('lessons.generate.form')
        ],
        [
          'label' => 'Info & File',
          'description' => 'Kelola file pembelajaran',
          'route' => route('info.admin.list')
        ],
        [
          'label' => 'Trip Guru',
          'description' => 'Pantau penggunaan poin',
          'route' => route('trips.index')
        ],
        [
          'label' => 'Pembayaran',
          'description' => 'Verifikasi pembayaran siswa',
          'route' => route('pay.list')
        ],
        [
          'label' => 'Absensi',
          'description' => 'Laporan kehadiran siswa',
          'route' => route('attendance.admin')
        ],
      ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      @foreach ($quickLinks as $link)
        <a href="{{ $link['route'] }}" class="group bg-white border border-slate-200 rounded-xl p-6 shadow-sm hover:shadow-md transition flex flex-col focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-600">
          <div class="flex items-center justify-between mb-3">
            <h3 class="text-base font-semibold text-slate-900">{{ $link['label'] }}</h3>
            <svg class="w-5 h-5 text-slate-300 group-hover:text-indigo-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 5l7 7-7 7" />
            </svg>
          </div>
          <p class="text-sm text-slate-500 flex-grow">{{ $link['description'] }}</p>
          <span class="mt-4 text-sm font-medium text-indigo-600">Lihat detail</span>
        </a>
      @endforeach
    </div>
  </div>
</x-admin-layout>
