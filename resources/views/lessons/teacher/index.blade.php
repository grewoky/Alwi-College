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

            <!-- Schedule Table -->
            <div class="bg-white rounded-md shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                                        <div class="overflow-x-auto -mx-4 sm:mx-0">
                                            <table class="min-w-[900px] w-full">
                        <thead class="bg-gray-100 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Tanggal</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Kelas</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Mata Pelajaran</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Jam</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lessons as $lesson)
                                <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($lesson->date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $lesson->classRoom->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                        {{ $lesson->subject?->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        @if($lesson->start_time && $lesson->end_time)
                                            {{ $lesson->start_time }} - {{ $lesson->end_time }}
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                        <p class="font-medium">Belum ada jadwal mengajar</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                                            </table>
                                        </div>
                </div>

                @if($lessons->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        {{ $lessons->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
