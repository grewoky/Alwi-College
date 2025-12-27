<x-app-layout>
    <x-slot name="title">Absensi • Data Kehadiran Murid</x-slot>

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">📊 Data Kehadiran Murid</h1>
                <p class="text-gray-600">Rekapitulasi absensi yang sudah Anda catat</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="text-3xl font-bold text-blue-500">{{ $totalSessions }}</div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Total Absen</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="text-3xl font-bold text-green-500">{{ $hadir }}</div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Hadir</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                    <div class="flex items-center">
                        <div class="text-3xl font-bold text-red-500">{{ $tidakHadir }}</div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Tidak Hadir</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center">
                        <div class="text-3xl font-bold text-yellow-500">{{ $izin }}</div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Izin</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
                    <div class="flex items-center">
                        <div class="text-3xl font-bold text-orange-500">{{ $sakit }}</div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Sakit</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Button to Mark Attendance -->
            <div class="mb-8">
                <a href="{{ route('attendance.mark.select') }}" 
                   class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Input Absensi Baru
                </a>
            </div>

            <!-- Attendance Records Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Siswa</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Kelas</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
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
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $attendance->created_at->format('d M Y H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="text-4xl mb-2">📭</div>
                                        <p class="text-lg font-semibold">Belum ada data absensi</p>
                                        <p class="text-sm">Mulai dengan menginput absensi kelas Anda</p>
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

        </div>
    </div>
</x-app-layout>
