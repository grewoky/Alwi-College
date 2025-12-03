<x-admin-layout>
    <x-slot name="title">Jadwal Pelajaran • Admin</x-slot>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Link -->
            <div class="mb-6">
                <a href="{{ route('lessons.admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-200 bg-white text-slate-700 font-medium shadow-sm">
                    <span class="text-lg">←</span>
                    <span>Kembali ke Dashboard Jadwal</span>
                </a>
            </div>
            
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Manajemen Jadwal Pelajaran</h1>
                <p class="text-gray-600">Lihat dan kelola jadwal pelajaran semua kelas</p>
            </div>

            <!-- Action Buttons -->
            <div class="mb-6 flex flex-wrap gap-3">
                <a href="{{ route('lessons.generate.form') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md font-medium">
                    Tambah Jadwal
                </a>
                <a href="{{ route('lessons.logs.deleted') }}" class="px-4 py-2 bg-red-500 text-white rounded-md font-medium">
                    Log Terhapus
                </a>
                <a href="{{ route('lessons.logs.expired') }}" class="px-4 py-2 bg-red-400 text-white rounded-md font-medium">
                    Log Kadaluarsa
                </a>
            </div>

            <!-- Filters Section -->
            <div class="bg-white rounded-md shadow-sm p-6 mb-6">
                <h3 class="font-semibold text-lg mb-4 text-gray-900">Filter</h3>
                <form method="GET" action="{{ route('lessons.admin') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Teacher Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pengajar</label>
                        <select name="teacher_id" class="w-full px-3 py-2 rounded-md border border-gray-300 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">-- Semua --</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="date" value="{{ request('date') }}" 
                               class="w-full px-3 py-2 rounded-md border border-gray-300 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Sort -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Urutkan</label>
                        <select name="sort" class="w-full px-3 py-2 rounded-md border border-gray-300 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="date_desc" {{ request('sort', 'date_desc') === 'date_desc' ? 'selected' : '' }}>Tanggal terbaru</option>
                            <option value="teacher_asc" {{ request('sort') === 'teacher_asc' ? 'selected' : '' }}>Nama pengajar (A-Z)</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2 items-end">
                        <button type="submit" class="flex-1 px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium transition">
                            Cari
                        </button>
                        <a href="{{ route('lessons.admin') }}" class="flex-1 px-3 py-2 bg-gray-300 text-gray-900 rounded-md hover:bg-gray-400 font-medium text-center transition">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Schedule Table -->
            <div class="bg-white rounded-md shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-[900px] w-full">
                        <thead>
                            <tr class="bg-gray-100 border-b border-gray-200">
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Tanggal</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Mata Pelajaran</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Kelas</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Pengajar</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Jam</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lessons as $lesson)
                                <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($lesson->date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                        {{ $lesson->subject?->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $lesson->classRoom->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $lesson->teacher->user->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        @if($lesson->start_time && $lesson->end_time)
                                            {{ $lesson->start_time }} - {{ $lesson->end_time }}
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('lessons.edit', $lesson) }}" 
                                               class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm font-medium transition">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('lessons.destroy', $lesson) }}" 
                                                  style="display: inline;"
                                                  onsubmit="return confirm('Hapus jadwal ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-sm font-medium transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        <p class="font-medium">Belum ada jadwal pelajaran</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($lessons->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        {{ $lessons->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

</x-admin-layout>
