<x-app-layout>
    <x-slot name="title">Absensi • Pencatatan</x-slot>

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-8">
                <a href="{{ route('attendance.teacher') }}" class="text-blue-600 hover:underline mb-4 inline-block">← Kembali</a>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">📝 Pencatatan Absensi</h1>
                <p class="text-gray-600">Kelas: <strong>{{ $classRoom->name }}</strong> ({{ $students->count() }} siswa)</p>
            </div>

            <!-- Success Message -->
            @if(session('ok'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
                    ✓ {{ session('ok') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 text-yellow-700 rounded-lg">
                    ⚠ {{ session('warning') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                    ✗ {{ session('error') }}
                </div>
            @endif

            <!-- Attendance Form -->
            <form method="POST" action="{{ route('attendance.mark', $classRoom->id) }}" class="bg-white rounded-lg shadow-md overflow-hidden">
                @csrf

                <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-800 text-white">
                    <h2 class="text-xl font-bold">📋 Daftar Siswa</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-100 border-b">
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 w-1/3">Nama Siswa</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Hadir</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Tidak Hadir</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Izin</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Sakit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                @php
                                    $existingStatus = $lesson ? $lesson->attendances->where('student_id', $student->id)->first()?->status : null;
                                @endphp
                                <tr class="border-b hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                        {{ $student->user->name }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="radio" name="attendances[{{ $student->id }}]" value="present"
                                               {{ $existingStatus === 'present' ? 'checked' : '' }}
                                               class="w-5 h-5 text-green-600 cursor-pointer">
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="radio" name="attendances[{{ $student->id }}]" value="alpha"
                                               {{ $existingStatus === 'alpha' ? 'checked' : '' }}
                                               class="w-5 h-5 text-red-600 cursor-pointer">
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="radio" name="attendances[{{ $student->id }}]" value="izin"
                                               {{ $existingStatus === 'izin' ? 'checked' : '' }}
                                               class="w-5 h-5 text-yellow-600 cursor-pointer">
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="radio" name="attendances[{{ $student->id }}]" value="sakit"
                                               {{ $existingStatus === 'sakit' ? 'checked' : '' }}
                                               class="w-5 h-5 text-blue-600 cursor-pointer">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t flex gap-4">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold transition">
                        💾 Simpan Absensi
                    </button>
                    <a href="{{ route('attendance.teacher') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 font-bold transition">
                        Batal
                    </a>
                </div>
            </form>

        </div>
    </div>

</x-app-layout>
