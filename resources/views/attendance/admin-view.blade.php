<x-app-layout>
    <x-slot name="title">Absensi • Admin Panel</x-slot>

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-8 flex justify-between items-start">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">📊 Pantau Kehadiran Siswa</h1>
                    <p class="text-gray-600">Data absensi bulan <strong>{{ $currentMonth }}</strong> (Read-only - Hanya Pantau)</p>
                </div>
                
                <!-- Export CSV Button -->
                <div class="flex gap-2">
                    <form action="{{ route('attendance.export.csv') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="month" value="{{ $startOfMonth->month }}">
                        <input type="hidden" name="year" value="{{ $startOfMonth->year }}">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold shadow transition-colors duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Export CSV
                        </button>
                    </form>
                </div>
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

            <!-- Attendance Records Grouped by School -->
            @php
                $attendancesBySchool = $attendancesPaginated->getCollection()->groupBy(function($attendance) {
                    return optional(optional($attendance->student)->classRoom)->school->name ?? 'Sekolah Tidak Diketahui';
                });
            @endphp

            @if($attendancesPaginated->isEmpty())
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="overflow-x-auto">
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
                            <tbody>
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                                        Belum ada data absensi untuk bulan <strong>{{ $currentMonth }}</strong>.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                @foreach($attendancesBySchool as $schoolName => $schoolAttendances)
                    <div class="mb-8">
                        <!-- School Header -->
                        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-t-lg p-6">
                            <h2 class="text-2xl font-bold">🏫 {{ $schoolName }}</h2>
                            <p class="text-blue-100 mt-1">{{ $schoolAttendances->count() }} catatan absensi</p>
                        </div>

                        <!-- Attendance Table for this School -->
                        <div class="bg-white rounded-b-lg shadow overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-100 border-b">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Siswa</th>
                                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Kelas</th>
                                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Counter 30 Hari</th>
                                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Guru Penginput</th>
                                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($schoolAttendances as $attendance)
                                            <tr class="hover:bg-gray-50 transition-colors">
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
                                                <td class="px-6 py-4 text-sm">
                                                    @php
                                                        $tracker = $attendance->student->attendanceTracker;
                                                        $count = $tracker?->attendance_count ?? 0;
                                                        $percentage = ($count / 30) * 100;
                                                    @endphp
                                                    <div class="flex items-center gap-2">
                                                        <div class="flex-1">
                                                            <div class="text-sm font-semibold text-gray-900">{{ $count }}/30</div>
                                                            <div class="w-24 h-2 bg-gray-200 rounded-full overflow-hidden">
                                                                <div class="h-full bg-blue-500" style="width: {{ $percentage }}%"></div>
                                                            </div>
                                                        </div>
                                                        @if($count >= 30)
                                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-800">✓ Reset</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    {{ $attendance->lesson->teacher->user->name ?? '-' }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-600">
                                                    {{ $attendance->created_at->format('d M Y H:i') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            <!-- Pagination -->
            @if($attendancesPaginated->hasPages())
                <div class="mt-8">
                    {{ $attendancesPaginated->links() }}
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
