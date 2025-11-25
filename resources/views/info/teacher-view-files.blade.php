<x-app-layout>
    <x-slot name="title">Dokumen Siswa • Guru</x-slot>

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">📚 Dokumen Siswa</h1>
                <p class="text-gray-600">Lihat file yang diupload oleh siswa Anda</p>
            </div>

            <!-- Filters -->
            <div class="mb-6 bg-white rounded-lg shadow p-6">
                <form method="GET" action="{{ route('info.teacher.student-files') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Class Filter (Hanya Kelas 10, 11, 12) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">🏫 Kelas (Anak Bangau)</label>
                            <select name="class_room_id" class="w-full border-2 border-gray-300 rounded-lg p-2 focus:border-green-600 focus:ring-2 focus:ring-green-600">
                                <option value="">-- Semua Kelas --</option>
                                @foreach($classRooms as $class)
                                    <option value="{{ $class->id }}" @selected(request('class_room_id') == $class->id)>
                                        Kelas {{ $class->grade }} - {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Subject Filter -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">📖 Matapelajaran</label>
                            <input type="text" name="subject" placeholder="Cari matapelajaran..." 
                                class="w-full border-2 border-gray-300 rounded-lg p-2 focus:border-green-600 focus:ring-2 focus:ring-green-600"
                                value="{{ request('subject') }}">
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition">
                            🔍 Filter
                        </button>
                        <a href="{{ route('info.teacher.student-files') }}" class="px-6 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 font-semibold transition">
                            ⟲ Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Files Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Siswa</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Kelas</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Judul</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Matapelajaran</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Tipe File</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Tanggal Upload</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">📊 Kehadiran</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($files as $file)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="font-semibold">{{ $file->student->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $file->student->user->email ?? ('ID: ' . $file->student->id) }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        {{ $file->student->classRoom->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="font-semibold">{{ $file->title }}</div>
                                    @if($file->material)
                                        <div class="text-xs text-gray-500">{{ $file->material }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $file->subject ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ strtoupper($file->file_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $file->created_at->format('d M Y H:i') }}
                                </td>
                                <!-- Kolom Presentase Kehadiran -->
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @php
                                        $totalLessons = \App\Models\Lesson::whereHas('classRoom', function($q) {
                                            $q->whereIn('grade', [10, 11, 12]);
                                        })->count();
                                        
                                        if ($totalLessons > 0) {
                                            $presentCount = \App\Models\Attendance::where('student_id', $file->student->id)
                                                ->whereIn('status', ['hadir', 'present', '1', 'hadir', 'Hadir'])
                                                ->count();
                                            $percentage = round(($presentCount / $totalLessons) * 100, 1);
                                        } else {
                                            $percentage = 0;
                                            $presentCount = 0;
                                        }
                                    @endphp
                                    
                                    @if($percentage >= 80)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                            ✓ {{ $percentage }}%
                                        </span>
                                    @elseif($percentage >= 70)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                                            ⚠ {{ $percentage }}%
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                            ✗ {{ $percentage }}%
                                        </span>
                                    @endif
                                    <div class="text-xs text-gray-500 mt-1">
                                        ({{ $presentCount }}/{{ $totalLessons }} pertemuan)
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($file->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($file->file_path))
                                        <a href="{{ route('info.teacher.download', $file) }}" 
                                           class="text-green-600 hover:text-green-700 font-semibold">
                                            ⬇️ Download
                                        </a>
                                    @else
                                        <span class="text-gray-400">File tidak tersedia</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="text-4xl mb-2">📭</div>
                                        <p class="text-lg font-semibold">Tidak ada dokumen</p>
                                        <p class="text-sm">Siswa belum mengunggah file apapun</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($files->hasPages())
                <div class="mt-6">
                    {{ $files->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
