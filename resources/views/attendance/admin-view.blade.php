<x-app-layout>
    <x-slot name="title">Absensi • Admin Panel</x-slot>

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">📊 Pantau Kehadiran Siswa</h1>
                <p class="text-gray-600">Data absensi bulan <strong>{{ $currentMonth }}</strong> (Read-only - Hanya Pantau)</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="text-3xl font-bold text-blue-500">{{ $stats['totalRecords'] }}</div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Total Absen</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="text-3xl font-bold text-green-500">{{ $stats['hadir'] }}</div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Hadir</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                    <div class="flex items-center">
                        <div class="text-3xl font-bold text-red-500">{{ $stats['tidakHadir'] }}</div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Tidak Hadir</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center">
                        <div class="text-3xl font-bold text-yellow-500">{{ $stats['izin'] }}</div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Izin</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
                    <div class="flex items-center">
                        <div class="text-3xl font-bold text-orange-500">{{ $stats['sakit'] }}</div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Sakit</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Records Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Siswa</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Kelas</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Guru Penginput</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($attendances as $attendance)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="font-semibold">{{ $attendance->student->user->name ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">ID: {{ $attendance->student->id }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        Kelas {{ $attendance->student->classRoom->grade ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($attendance->status === 'present')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            ✓ Hadir
                                        </span>
                                    @elseif($attendance->status === 'alpha')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            ✗ Tidak Hadir
                                        </span>
                                    @elseif($attendance->status === 'izin')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                            ~ Izin
                                        </span>
                                    @elseif($attendance->status === 'sakit')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                            🏥 Sakit
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $attendance->lesson->teacher->user->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $attendance->created_at->format('d M Y H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="text-4xl mb-2">📭</div>
                                        <p class="text-lg font-semibold">Belum ada data absensi</p>
                                        <p class="text-sm">Data absensi untuk bulan ini akan ditampilkan di sini</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($attendances->hasPages())
                <div class="mt-8">
                    {{ $attendances->links() }}
                </div>
            @endif

            <!-- Info Banner -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex items-start gap-4">
                    <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-blue-900 mb-1">Informasi Penting</h3>
                        <p class="text-sm text-blue-800">
                            • Data absensi ditampilkan untuk bulan <strong>{{ $currentMonth }}</strong> saja<br>
                            • Data akan di-reset otomatis setiap awal bulan<br>
                            • Anda hanya dapat memantau, tidak dapat mengubah data absensi<br>
                            • Guru dapat menginput absensi, dan hanya 1x per hari per kelas
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
                <div class="mb-12">
                    <!-- School Header -->
                    <h2 class="text-3xl font-bold mb-6 text-gray-900 pb-3 border-b-4 border-blue-600">
                        🏫 {{ $schoolName }}
                    </h2>

                    <!-- Grade Groups (10, 11, 12) -->
                    @foreach($gradeGroups as $grade => $classrooms)
                        <div class="mb-8">
                            <h3 class="text-2xl font-bold mb-4 text-gray-800 pb-2 border-l-4 border-blue-400 pl-4">
                                Kelas {{ $grade }}
                            </h3>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                @foreach($classrooms as $classroom)
                                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                                        <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-800 text-white">
                                            <h4 class="text-lg font-bold">{{ $classroom->name }}</h4>
                                            <p class="text-sm text-blue-100">{{ $classroom->students()->count() }} siswa</p>
                                        </div>

                                        <div class="overflow-x-auto">
                                            <div class="overflow-x-auto -mx-4 sm:mx-0">
                                                <table class="min-w-[900px] w-full text-sm">
                                                <thead>
                                                    <tr class="bg-gray-100 border-b">
                                                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Nama Siswa</th>
                                                        <th class="px-4 py-2 text-center font-semibold text-gray-700">✓ Hadir</th>
                                                        <th class="px-4 py-2 text-center font-semibold text-gray-700">✗ Tidak</th>
                                                        <th class="px-4 py-2 text-center font-semibold text-gray-700">📋 Izin</th>
                                                        <th class="px-4 py-2 text-center font-semibold text-gray-700">🏥 Sakit</th>
                                                        <th class="px-4 py-2 text-center font-semibold text-gray-700">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($classroom->students as $student)
                                                        @php
                                                            $summary = $attendanceSummary->where('student_id', $student->id);
                                                            $hadir = $summary->where('status', 'present')->sum('count');
                                                            $tidakHadir = $summary->where('status', 'alpha')->sum('count');
                                                            $izin = $summary->where('status', 'izin')->sum('count');
                                                            $sakit = $summary->where('status', 'sakit')->sum('count');
                                                        @endphp
                                                        <tr class="border-b hover:bg-gray-50 transition-colors">
                                                            <td class="px-4 py-3 font-medium text-gray-900">
                                                                {{ $student->user->name ?? 'N/A' }}
                                                            </td>
                                                            <td class="px-4 py-3 text-center">
                                                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full font-bold text-xs">
                                                                    {{ $hadir }}
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-3 text-center">
                                                                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full font-bold text-xs">
                                                                    {{ $tidakHadir }}
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-3 text-center">
                                                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full font-bold text-xs">
                                                                    {{ $izin }}
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-3 text-center">
                                                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full font-bold text-xs">
                                                                    {{ $sakit }}
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-3 text-center">
                                                                @if($student->user && $student->user->email)
                                                                    <form action="{{ route('admin.students.clear_email', $student) }}" method="POST" onsubmit="return confirm('Yakin hapus email siswa ini? Aksi ini akan mengganti email dengan placeholder dan tidak dapat dikembalikan.');">
                                                                        @csrf
                                                                        <button type="submit" class="px-2 py-1 bg-red-600 text-white rounded text-xs">Hapus Email</button>
                                                                    </form>
                                                                @else
                                                                    <span class="text-slate-400 text-xs">—</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                                                Belum ada siswa di kelas ini
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @empty
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <p class="text-gray-500 text-lg">📭 Belum ada data classroom</p>
                </div>
            @endforelse

        </div>
    </div>

</x-app-layout>
