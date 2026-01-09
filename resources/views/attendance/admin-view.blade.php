<x-app-layout>
    <x-slot name="title">Absensi • Admin Panel</x-slot>

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-8">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">📊 Pantau Kehadiran Siswa</h1>
                    <p class="text-gray-600">(Read-only - Hanya Pantau)</p>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="mb-6 border-b border-gray-200">
                <nav class="flex gap-4" aria-label="Tabs">
                    <button id="tab-current" onclick="switchTab('current')" class="tab-button active py-3 px-4 border-b-2 border-blue-600 text-blue-600 font-semibold hover:text-blue-700 transition-colors">
                        📅 Bulan Ini: {{ $currentMonth }}
                    </button>
                    <button id="tab-previous" onclick="switchTab('previous')" class="tab-button py-3 px-4 border-b-2 border-transparent text-gray-700 font-semibold hover:text-blue-600 transition-colors">
                        📆 Bulan Lalu: {{ $previousMonth }}
                    </button>
                </nav>
            </div>

            <!-- TAB: CURRENT MONTH -->
            <div id="content-current" class="tab-content block">
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
                                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Kehadiran (Hari)</th>
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
            </div>
            <!-- END: TAB CURRENT MONTH -->

            <!-- TAB: PREVIOUS MONTH -->
            <div id="content-previous" class="tab-content hidden">
            <!-- Statistics Cards Previous Month -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="text-3xl font-bold text-blue-500">{{ $previousMonthStats['totalRecords'] }}</div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Total Absen</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="text-3xl font-bold text-green-500">{{ $previousMonthStats['hadir'] }}</div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Hadir</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                    <div class="flex items-center">
                        <div class="text-3xl font-bold text-red-500">{{ $previousMonthStats['tidakHadir'] }}</div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Tidak Hadir</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center">
                        <div class="text-3xl font-bold text-yellow-500">{{ $previousMonthStats['izin'] }}</div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Izin</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
                    <div class="flex items-center">
                        <div class="text-3xl font-bold text-orange-500">{{ $previousMonthStats['sakit'] }}</div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Sakit</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Records Grouped by School - Previous Month -->
            @php
                $previousAttendancesBySchool = $previousMonthAttendancesPaginated->getCollection()->groupBy(function($attendance) {
                    return optional(optional($attendance->student)->classRoom)->school->name ?? 'Sekolah Tidak Diketahui';
                });
            @endphp

            @if($previousMonthAttendancesPaginated->isEmpty())
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
                                        Belum ada data absensi untuk bulan <strong>{{ $previousMonth }}</strong>.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                @foreach($previousAttendancesBySchool as $schoolName => $schoolAttendances)
                    <div class="mb-8">
                        <!-- School Header -->
                        <div class="bg-gradient-to-r from-purple-600 to-purple-800 text-white rounded-t-lg p-6">
                            <h2 class="text-2xl font-bold">🏫 {{ $schoolName }}</h2>
                            <p class="text-purple-100 mt-1">{{ $schoolAttendances->count() }} catatan absensi</p>
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
                                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Kehadiran (Hari)</th>
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
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
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
                                                                <div class="h-full bg-purple-500" style="width: {{ $percentage }}%"></div>
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

            <!-- Pagination Previous Month -->
            @if($previousMonthAttendancesPaginated->hasPages())
                <div class="mt-8">
                    {{ $previousMonthAttendancesPaginated->links() }}
                </div>
            @endif
            </div>
            <!-- END: TAB PREVIOUS MONTH -->

            <!-- Info Banner -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex items-start gap-4">
                    <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-blue-900 mb-1">Informasi Penting</h3>
                        <p class="text-sm text-blue-800">
                            • Anda dapat memantau data absensi bulan ini dan bulan sebelumnya<br>
                            • Data akan di-reset otomatis setiap awal bulan<br>
                            • Anda hanya dapat memantau, tidak dapat mengubah data absensi<br>
                            • Guru dapat menginput absensi, dan hanya 1x per hari per kelas
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function switchTab(tabName) {
            // Hide all tab contents
            const contentTabs = document.querySelectorAll('.tab-content');
            contentTabs.forEach(tab => {
                tab.classList.add('hidden');
                tab.classList.remove('block');
            });

            // Remove active state from all buttons
            const tabButtons = document.querySelectorAll('.tab-button');
            tabButtons.forEach(button => {
                button.classList.remove('border-blue-600', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-700');
            });

            // Show selected tab content
            const selectedContent = document.getElementById('content-' + tabName);
            if (selectedContent) {
                selectedContent.classList.remove('hidden');
                selectedContent.classList.add('block');
            }

            // Activate selected tab button
            const selectedButton = document.getElementById('tab-' + tabName);
            if (selectedButton) {
                selectedButton.classList.remove('border-transparent', 'text-gray-700');
                selectedButton.classList.add('border-blue-600', 'text-blue-600');
            }
        }
    </script>
</x-app-layout>
