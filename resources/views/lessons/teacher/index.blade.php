<x-app-layout>
    <x-slot name="title">Jadwal Mengajar • Guru</x-slot>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('teacher.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-200 bg-white text-slate-700 font-medium shadow-sm">
                    <span class="text-lg">←</span>
                    <span>Kembali ke Dashboard Guru</span>
                </a>
            </div>
            
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Jadwal Mengajar</h1>
                <p class="text-gray-600">Guru: <strong class="text-indigo-600">{{ $teacher->user->name }}</strong></p>
            </div>

            <!-- Filters Section -->
            <div class="bg-white rounded-md shadow-sm p-6 mb-6">
                <h3 class="font-semibold text-lg mb-4 text-gray-900">Filter</h3>

                <!-- Grade Filter Buttons -->
                <div class="mb-6">
                    <p class="text-sm font-medium text-gray-700 mb-3">Pilih Kelas:</p>
                    <div class="flex gap-2 flex-wrap">
                        <a href="{{ route('lessons.teacher') }}" 
                           class="inline-flex items-center px-4 py-2 rounded-lg font-medium transition {{ !request('grade') ? 'bg-indigo-600 text-white shadow-sm border border-indigo-600' : 'bg-white border border-slate-200 text-slate-600 hover:border-indigo-300 hover:text-indigo-600' }}">
                            Semua
                        </a>
                        <a href="{{ route('lessons.teacher', ['grade' => '10']) }}" 
                           class="inline-flex items-center px-4 py-2 rounded-lg font-medium transition {{ request('grade') == '10' ? 'bg-indigo-600 text-white shadow-sm border border-indigo-600' : 'bg-white border border-slate-200 text-slate-600 hover:border-indigo-300 hover:text-indigo-600' }}">
                            Kelas 10
                        </a>
                        <a href="{{ route('lessons.teacher', ['grade' => '11']) }}" 
                           class="inline-flex items-center px-4 py-2 rounded-lg font-medium transition {{ request('grade') == '11' ? 'bg-indigo-600 text-white shadow-sm border border-indigo-600' : 'bg-white border border-slate-200 text-slate-600 hover:border-indigo-300 hover:text-indigo-600' }}">
                            Kelas 11
                        </a>
                        <a href="{{ route('lessons.teacher', ['grade' => '12']) }}" 
                           class="inline-flex items-center px-4 py-2 rounded-lg font-medium transition {{ request('grade') == '12' ? 'bg-indigo-600 text-white shadow-sm border border-indigo-600' : 'bg-white border border-slate-200 text-slate-600 hover:border-indigo-300 hover:text-indigo-600' }}">
                            Kelas 12
                        </a>
                    </div>
                </div>

                <!-- Additional Filters -->
                <form method="GET" action="{{ route('lessons.teacher') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @if(request('grade'))
                        <input type="hidden" name="grade" value="{{ request('grade') }}">
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="date" value="{{ request('date') }}" 
                               class="w-full px-3 py-2 rounded-md border border-gray-300 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelas Spesifik</label>
                        <select name="class_room_id" class="w-full px-3 py-2 rounded-md border border-gray-300 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">-- Semua --</option>
                            @foreach($teacherClasses as $class)
                                <option value="{{ $class->id }}" @selected(request('class_room_id') == $class->id)>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex gap-2 items-end">
                        <button type="submit" class="flex-1 px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium transition">
                            Cari
                        </button>
                        <a href="{{ request('grade') ? route('lessons.teacher', ['grade' => request('grade')]) : route('lessons.teacher') }}" 
                           class="flex-1 px-3 py-2 rounded-lg font-medium text-center transition bg-white border border-slate-200 text-slate-700 hover:border-indigo-300 hover:text-indigo-600">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Schedule Grouped by School -->
            @php
                $lessonsBySchool = $lessons->groupBy(fn($lesson) => $lesson->classRoom->school->name ?? 'Sekolah Tidak Diketahui');
            @endphp

            @forelse($lessonsBySchool as $schoolName => $schoolLessons)
                <div class="mb-8">
                    <!-- School Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-t-lg p-6">
                        <h2 class="text-2xl font-bold">🏫 {{ $schoolName }}</h2>
                        <p class="text-blue-100 mt-1">{{ $schoolLessons->count() }} jadwal mengajar</p>
                    </div>

                    <!-- Schedule Table for this School -->
                    <div class="bg-white rounded-b-lg shadow overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-100 border-b border-gray-200">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Kelas</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Mata Pelajaran</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Jam</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($schoolLessons as $lesson)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                                {{ \Carbon\Carbon::parse($lesson->date)->format('d M Y') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                    {{ $lesson->classRoom->name }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ $lesson->subject?->name ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                @if($lesson->start_time && $lesson->end_time)
                                                    <span class="text-green-700 font-medium">
                                                        {{ \Carbon\Carbon::parse($lesson->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($lesson->end_time)->format('H:i') }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-gray-600 text-lg font-medium">Belum ada jadwal mengajar</p>
                    <p class="text-gray-500 text-sm mt-1">Jadwal mengajar Anda akan ditampilkan di sini</p>
                </div>
            @endforelse

            <!-- Pagination -->
            @if($lessons->hasPages())
                <div class="mt-8">
                    {{ $lessons->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
