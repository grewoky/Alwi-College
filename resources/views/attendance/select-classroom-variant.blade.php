<x-app-layout>
    <x-slot name="title">Absensi • Pilih Varian Kelas</x-slot>

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('attendance.mark.select') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <span>Kembali ke Pilih Sekolah</span>
                </a>
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

            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">📚 Pilih Varian Kelas</h1>
                <p class="text-gray-600">
                    Sekolah: <strong>{{ $schoolName }}</strong> | 
                    Kelas: <strong>{{ $grade }}</strong>
                </p>
            </div>

            <!-- Classroom Variants Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($classRooms as $classRoom)
                    <a href="{{ route('attendance.mark', $classRoom->id) }}" 
                       class="block p-6 rounded-lg border-2 border-gray-200 hover:border-green-500 hover:shadow-lg transition group bg-white"
                       data-classroom-id="{{ $classRoom->id }}"
                       data-classroom-name="{{ $classRoom->name }}">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-2xl font-bold text-gray-900 group-hover:text-green-600 transition">
                                    {{ $classRoom->name }}
                                </h3>
                                <p class="text-sm text-gray-600 mt-2">
                                    🏫 {{ $schoolName }}
                                </p>
                            </div>
                            <svg class="w-6 h-6 text-gray-400 group-hover:text-green-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>

                        <!-- Grade & Class Info -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 7H7v6h6V7z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900">
                                    Kelas {{ $classRoom->grade }}
                                </span>
                            </div>
                        </div>

                        <p class="text-xs text-gray-500 group-hover:text-green-600 transition mt-4 font-medium">Klik untuk input absensi →</p>
                    </a>
                @empty
                    <div class="col-span-full bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 p-12 text-center">
                        <p class="text-gray-500 text-lg">Tidak ada kelas varian tersedia</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

</x-app-layout>
