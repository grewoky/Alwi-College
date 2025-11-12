<x-app-layout>
    <x-slot name="title">Absensi • Admin</x-slot>

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">📊 Ringkasan Absensi Seluruh Siswa</h1>
                <p class="text-gray-600">Lihat status kehadiran semua siswa berdasarkan classroom</p>
            </div>

            <!-- Grouped by School & Grade -->
            @forelse($groupedClasses as $schoolName => $gradeGroups)
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
                                            <table class="w-full text-sm">
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
