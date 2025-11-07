<x-app-layout>
    <x-slot name="title">Jadwal Pelajaran • Siswa</x-slot>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Jadwal Pelajaran</h1>
                <p class="text-gray-600">Kelas: <strong class="text-blue-600">{{ $student->classRoom->name }}</strong></p>
            </div>

            <!-- Filters Section -->
            <div class="bg-white rounded-md shadow-sm p-6 mb-6">
                <h3 class="font-semibold text-lg mb-4 text-gray-900">Filter</h3>

                <!-- Grade Filter Buttons -->
                <div class="mb-6">
                    <p class="text-sm font-medium text-gray-700 mb-3">Pilih Kelas:</p>
                    <div class="flex gap-2 flex-wrap">
                        <a href="{{ route('lessons.student') }}" 
                           class="px-4 py-2 rounded-md font-medium transition-colors {{ !request('grade') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                            Semua
                        </a>
                        <a href="{{ route('lessons.student', ['grade' => '10']) }}" 
                           class="px-4 py-2 rounded-md font-medium transition-colors {{ request('grade') == '10' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                            Kelas 10
                        </a>
                        <a href="{{ route('lessons.student', ['grade' => '11']) }}" 
                           class="px-4 py-2 rounded-md font-medium transition-colors {{ request('grade') == '11' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                            Kelas 11
                        </a>
                        <a href="{{ route('lessons.student', ['grade' => '12']) }}" 
                           class="px-4 py-2 rounded-md font-medium transition-colors {{ request('grade') == '12' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                            Kelas 12
                        </a>
                    </div>
                </div>

                <!-- Tanggal Filter -->
                <form method="GET" action="{{ route('lessons.student') }}" class="flex gap-2 flex-wrap items-end">
                    @if(request('grade'))
                        <input type="hidden" name="grade" value="{{ request('grade') }}">
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="date" value="{{ request('date') }}" 
                               class="px-3 py-2 rounded-md border border-gray-300 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium transition">
                        Cari
                    </button>
                    <a href="{{ request('grade') ? route('lessons.student', ['grade' => request('grade')]) : route('lessons.student') }}" 
                       class="px-4 py-2 bg-gray-300 text-gray-900 rounded-md hover:bg-gray-400 font-medium transition">
                        Reset
                    </a>
                </form>
            </div>

            <!-- Schedule Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($lessons as $lesson)
                    <div class="bg-white rounded-md shadow-sm hover:shadow-md transition-shadow overflow-hidden border-l-4 border-blue-600">
                        <div class="px-5 py-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">{{ $lesson->subject?->name ?? 'N/A' }}</h3>
                            
                            <div class="space-y-3 text-sm">
                                <div>
                                    <p class="text-gray-500 text-xs font-medium uppercase">Tanggal</p>
                                    <p class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($lesson->date)->format('d M Y') }}</p>
                                </div>

                                <div>
                                    <p class="text-gray-500 text-xs font-medium uppercase">Pengajar</p>
                                    <p class="text-gray-900 font-medium">{{ $lesson->teacher->user->name ?? 'N/A' }}</p>
                                </div>

                                <div>
                                    <p class="text-gray-500 text-xs font-medium uppercase">Jam</p>
                                    <p class="text-gray-900 font-medium">
                                        @if($lesson->start_time && $lesson->end_time)
                                            {{ $lesson->start_time }} - {{ $lesson->end_time }}
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </p>
                                </div>

                                <div class="pt-2 border-t border-gray-200">
                                    <p class="text-gray-500 text-xs font-medium uppercase">Kelas</p>
                                    <p class="text-gray-900 font-medium">{{ $lesson->classRoom->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-md shadow-sm p-12 text-center">
                        <p class="text-gray-500 font-medium">Belum ada jadwal pelajaran</p>
                    </div>
                @endforelse
            </div>

            @if($lessons->hasPages())
                <div class="mt-8">
                    {{ $lessons->links() }}
                </div>
            @endif

        </div>
    </div>

</x-app-layout>
