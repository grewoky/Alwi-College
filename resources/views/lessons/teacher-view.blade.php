<x-app-layout>
    <x-slot name="title">Jadwal Mengajar • Guru</x-slot>

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">📅 Jadwal Mengajar Saya</h1>
                <p class="text-gray-600">Pengajar: <strong>{{ $teacher->user->name }}</strong></p>
            </div>

            <!-- Filter -->
            <div class="mb-6">
              <!-- Grade Filter Buttons -->
              <div class="mb-6">
                <p class="text-sm font-semibold text-gray-900 mb-3">Filter Jadwal Berdasarkan Kelas:</p>
                <div class="flex gap-3 flex-wrap">
                  <a href="{{ route('lessons.teacher') }}" 
                     class="px-6 py-2 rounded-lg font-semibold transition-all duration-300 {{ !request('grade') ? 'bg-green-600 text-white shadow-lg' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    📚 Semua Kelas
                  </a>
                  <a href="{{ route('lessons.teacher', ['grade' => '10']) }}" 
                     class="px-6 py-2 rounded-lg font-semibold transition-all duration-300 {{ request('grade') == '10' ? 'bg-green-600 text-white shadow-lg' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    📖 Kelas 10
                  </a>
                  <a href="{{ route('lessons.teacher', ['grade' => '11']) }}" 
                     class="px-6 py-2 rounded-lg font-semibold transition-all duration-300 {{ request('grade') == '11' ? 'bg-green-600 text-white shadow-lg' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    📖 Kelas 11
                  </a>
                  <a href="{{ route('lessons.teacher', ['grade' => '12']) }}" 
                     class="px-6 py-2 rounded-lg font-semibold transition-all duration-300 {{ request('grade') == '12' ? 'bg-green-600 text-white shadow-lg' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    📖 Kelas 12
                  </a>
                </div>
              </div>

              <!-- Additional Filters (Date & Class Dropdown) -->
              <form method="GET" action="{{ route('lessons.teacher') }}" class="flex gap-2 flex-wrap items-end">
                    @if(request('grade'))
                      <input type="hidden" name="grade" value="{{ request('grade') }}">
                    @endif
                    <div>
                      <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal</label>
                      <input type="date" name="date" value="{{ request('date') }}" 
                             class="px-4 py-2 rounded-lg border-2 border-gray-300 focus:ring-2 focus:ring-green-600 focus:border-green-600">
                    </div>
                    
                    <div>
                      <label class="block text-sm font-semibold text-gray-700 mb-2">Kelas</label>
                      <select name="class_room_id" class="px-4 py-2 rounded-lg border-2 border-gray-300 focus:ring-2 focus:ring-green-600 focus:border-green-600">
                          <option value="">-- Semua Kelas --</option>
                          @foreach($teacherClasses as $class)
                              <option value="{{ $class->id }}" @selected(request('class_room_id') == $class->id)>
                                  {{ $class->name }}
                              </option>
                          @endforeach
                      </select>
                    </div>
                    
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-bold transition">
                        🔍 Cari
                    </button>
                    <a href="{{ request('grade') ? route('lessons.teacher', ['grade' => request('grade')]) : route('lessons.teacher') }}" 
                       class="px-6 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 font-bold transition">
                        ⟲ Reset
                    </a>
                </form>
            </div>

            <!-- Schedule Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Tanggal</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Kelas</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Mata Pelajaran</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Jam</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($lessons as $lesson)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($lesson->date)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        {{ $lesson->classRoom->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $lesson->subject?->name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @if($lesson->start_time && $lesson->end_time)
                                        <span class="text-blue-600 font-semibold">{{ $lesson->start_time }} - {{ $lesson->end_time }}</span>
                                    @else
                                        <span class="text-gray-400 italic">Belum ditentukan</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="text-4xl mb-2">📭</div>
                                        <p class="text-lg font-semibold">Belum ada jadwal mengajar</p>
                                        <p class="text-sm">Jadwal Anda akan muncul di sini setelah admin membuat jadwal.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($lessons->hasPages())
                <div class="mt-6">
                    {{ $lessons->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
