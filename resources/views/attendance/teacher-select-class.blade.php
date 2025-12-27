<x-app-layout>
    <x-slot name="title">Absensi • Pilih Kelas</x-slot>

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">📊 Input Absensi</h1>
                <p class="text-gray-600">Pilih kelas untuk menginput absensi siswa</p>
            </div>

            @if(session('error'))
                <div class="mb-6 p-4 rounded-lg border border-red-200 bg-red-50 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-6 p-4 rounded-lg border border-yellow-200 bg-yellow-50 text-yellow-800">
                    {{ session('warning') }}
                </div>
            @endif

            <!-- Group by School -->
            @php
                $schoolOrder = ['Bangau', 'IGS', 'Kumbang', 'Negeri', 'Xavega'];
                $sortedSchools = collect($classesBySchoolAndGrade)->sortBy(function($item, $key) use ($schoolOrder) {
                    return array_search($key, $schoolOrder) !== false ? array_search($key, $schoolOrder) : 999;
                });
            @endphp

            @forelse($sortedSchools as $schoolName => $gradeGroups)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
                    <!-- School Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-8">
                        <h2 class="text-3xl font-bold">🏫 {{ $schoolName }}</h2>
                        @php
                            $totalClasses = collect($gradeGroups)->sum(fn($classes) => count($classes));
                        @endphp
                        <p class="text-blue-100 mt-2">{{ $totalClasses }} kelas tersedia</p>
                    </div>

                    <!-- Grades for this School -->
                    <div class="p-8">
                        @php
                            ksort($gradeGroups);
                        @endphp
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @forelse($gradeGroups as $grade => $classRooms)
                                <a href="{{ route('attendance.select.classroom', ['school' => $schoolName, 'grade' => $grade]) }}" 
                                   class="block p-8 rounded-lg border-2 border-gray-200 hover:border-green-500 hover:shadow-lg transition group bg-white text-center">
                                    <div class="mb-4">
                                        <div class="text-5xl font-bold text-gray-900 group-hover:text-green-600 transition">
                                            {{ $grade }}
                                        </div>
                                    </div>
                                    <h4 class="text-xl font-bold text-gray-900 group-hover:text-green-600 transition mb-2">
                                        Kelas {{ $grade }}
                                    </h4>
                                    <p class="text-sm text-gray-600">
                                        {{ count($classRooms) }} varian kelas
                                    </p>
                                    <p class="text-xs text-gray-500 group-hover:text-green-600 transition mt-4 font-medium">Klik untuk pilih varian →</p>
                                </a>
                            @empty
                                <p class="text-gray-500">Tidak ada kelas untuk sekolah ini</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                    <p class="text-gray-600 text-lg font-medium">Belum ada kelas</p>
                    <p class="text-gray-500 text-sm mt-1">Anda belum mengajar kelas apapun</p>
                </div>
            @endforelse

        </div>
    </div>

</x-app-layout>
