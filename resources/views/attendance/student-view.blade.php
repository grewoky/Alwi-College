<x-app-layout>
    <x-slot name="title">Absensi • Riwayat Kehadiran</x-slot>

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">📊 Riwayat Absensi</h1>
                <p class="text-gray-600">Kelas: <strong>{{ $classRoom->name }}</strong></p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
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

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="text-3xl font-bold text-blue-500">{{ $sakit }}</div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Sakit</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                    <div class="flex items-center">
                        <div class="text-3xl font-bold text-purple-500">{{ $totalSessions }}</div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Total</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-800 text-white">
                    <h2 class="text-xl font-bold">📋 Daftar Riwayat Absensi</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-100 border-b">
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Mata Pelajaran</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Pengajar</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $attendance)
                                <tr class="border-b hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-sm text-gray-800">
                                        {{ \Carbon\Carbon::parse($attendance->lesson->date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-800">
                                        {{ $attendance->lesson->subject->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-800">
                                        {{ $attendance->lesson->teacher->user->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @php
                                            $status = $attendance->status;
                                            $statusDisplay = [
                                                'present' => ['badge' => 'bg-green-100 text-green-800', 'label' => '✓ Hadir'],
                                                'alpha' => ['badge' => 'bg-red-100 text-red-800', 'label' => '✗ Tidak Hadir'],
                                                'izin' => ['badge' => 'bg-yellow-100 text-yellow-800', 'label' => '📋 Izin'],
                                                'sakit' => ['badge' => 'bg-blue-100 text-blue-800', 'label' => '🏥 Sakit'],
                                            ];
                                            $display = $statusDisplay[$status] ?? ['badge' => 'bg-gray-100 text-gray-800', 'label' => 'Unknown'];
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $display['badge'] }}">
                                            {{ $display['label'] }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                        <p class="text-lg">📭 Belum ada data absensi</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($attendances->hasPages())
                    <div class="px-6 py-4 border-t bg-gray-50">
                        {{ $attendances->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

</x-app-layout>
