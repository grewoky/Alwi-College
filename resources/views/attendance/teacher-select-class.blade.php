<x-app-layout>
    <x-slot name="title">Absensi • Pilih Kelas</x-slot>

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">📊 Input Absensi</h1>
                <p class="text-gray-600">Pilih kelas untuk menginput absensi siswa</p>
            </div>

            <!-- Class Selection by Grade -->
            <div class="space-y-8">
                @foreach([10, 11, 12] as $grade)
                    @if(isset($classesByGrade[$grade]) && $classesByGrade[$grade]->count() > 0)
                        <div class="bg-white rounded-lg shadow p-8">
                            <h2 class="text-2xl font-bold mb-6 text-gray-900 pb-4 border-b-2 border-blue-500">
                                Kelas {{ $grade }}
                            </h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($classesByGrade[$grade] as $classRoom)
                                    <a href="{{ route('attendance.mark', $classRoom->id) }}" 
                                       class="block p-6 rounded-lg border-2 border-gray-200 hover:border-green-500 hover:shadow-lg transition group">
                                        <div class="flex items-start justify-between mb-2">
                                            <div>
                                                <h3 class="text-lg font-bold text-gray-900 group-hover:text-green-600 transition">
                                                    {{ $classRoom->name }}
                                                </h3>
                                                <p class="text-sm text-gray-600">
                                                    {{ $classRoom->students()->count() }} Siswa
                                                </p>
                                            </div>
                                            <svg class="w-6 h-6 text-gray-400 group-hover:text-green-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                        <p class="text-xs text-gray-500">Klik untuk input absensi</p>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach

                @if(collect($classesByGrade)->flatten()->isEmpty())
                    <div class="bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 p-12 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                        <p class="text-gray-600 text-lg font-medium">Belum ada kelas</p>
                        <p class="text-gray-500 text-sm mt-1">Anda belum mengajar kelas apapun di kelas 10, 11, atau 12</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

</x-app-layout>
