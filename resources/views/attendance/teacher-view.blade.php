<x-app-layout>
    <x-slot name="title">Absensi • Pengajar</x-slot>

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">📊 Pencatatan Absensi</h1>
                <p class="text-gray-600">Pengajar: <strong>{{ auth()->user()->name }}</strong></p>
            </div>

            <!-- Class Selection -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-bold mb-4">👥 Pilih Kelas</h2>
                
                @if($groupedClasses->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-gray-500">Anda belum mengajar kelas apapun.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($groupedClasses as $prefix => $classes)
                            <div>
                                <h3 class="font-bold text-lg mb-3 text-gray-800 border-b pb-2">Blok {{ $prefix }}</h3>
                                @foreach($classes as $classroom)
                                    <div class="mb-2">
                                        <a href="{{ route('attendance.mark', $classroom->id) }}" 
                                           class="block p-4 rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 text-white hover:shadow-lg hover:scale-105 transition-transform">
                                            <div class="font-bold text-lg">{{ $classroom->name }}</div>
                                            <div class="text-sm text-blue-100">{{ $classroom->students()->count() }} siswa</div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>

</x-app-layout>
