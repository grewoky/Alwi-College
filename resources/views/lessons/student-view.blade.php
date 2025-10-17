<x-app-layout>
    <x-slot name="title">Jadwal Les • Siswa</x-slot>

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">📅 Jadwal Pelajaran</h1>
                <p class="text-gray-600">Kelas: <strong>{{ $student->classRoom->name }}</strong></p>
            </div>

            <!-- Filter -->
            <div class="mb-6 flex gap-4 flex-wrap">
                <form method="GET" action="{{ route('lessons.index') }}" class="flex gap-2">
                    <input type="date" name="date" value="{{ request('date') }}" 
                           class="px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold">
                        🔍 Filter
                    </button>
                    <a href="{{ route('lessons.index') }}" class="px-6 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 font-bold">
                        ⟲ Reset
                    </a>
                </form>
            </div>

            <!-- Schedule Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($lessons as $lesson)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow overflow-hidden border-l-4 border-blue-600">
                        <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                            <h3 class="text-lg font-bold">{{ $lesson->subject?->name ?? 'N/A' }}</h3>
                            <p class="text-sm text-blue-100">
                                📅 {{ \Carbon\Carbon::parse($lesson->date)->format('d M Y') }}
                            </p>
                        </div>

                        <div class="px-6 py-4">
                            <div class="mb-3">
                                <p class="text-sm text-gray-600">Pengajar</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $lesson->teacher->user->name ?? 'N/A' }}</p>
                            </div>

                            <div class="mb-3">
                                <p class="text-sm text-gray-600">Jam Pelajaran</p>
                                <p class="text-lg font-semibold text-gray-900">
                                    @if($lesson->start_time && $lesson->end_time)
                                        {{ $lesson->start_time }} - {{ $lesson->end_time }}
                                    @else
                                        <span class="text-gray-400">Belum ditentukan</span>
                                    @endif
                                </p>
                            </div>

                            <div class="pt-3 border-t">
                                <p class="text-sm text-gray-600">Ruang Kelas</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $lesson->classRoom->name }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-lg shadow p-12 text-center">
                        <p class="text-gray-500 text-lg">📭 Belum ada jadwal pelajaran</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($lessons->hasPages())
                <div class="mt-8">
                    {{ $lessons->links() }}
                </div>
            @endif

        </div>
    </div>

</x-app-layout>
