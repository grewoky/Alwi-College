<x-app-layout>
    <x-slot name="title">Jadwal Pelajaran • Siswa</x-slot>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <style>
                            .card-hover{transition:all .25s cubic-bezier(0.4,0,0.2,1)}
                            .card-hover:hover{transform:translateY(-4px);box-shadow:0 12px 24px rgba(0,0,0,0.08)}
                            .heading-inline{position:relative;display:inline-block}
                            .heading-inline::after{content:"";position:absolute;left:0;bottom:-10px;width:100%;height:4px;border-radius:9999px;background:linear-gradient(90deg,#3B63B5 0%, #6FA2FF 50%, #3B63B5 100%);opacity:.25}
                        </style>
            <div class="mb-6">
                <a href="{{ route('student.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-200 bg-white text-slate-700 font-medium shadow-sm">
                    <span class="text-lg">←</span>
                    <span>Kembali ke Dashboard Siswa</span>
                </a>
            </div>
            
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2"><span class="heading-inline">Jadwal Pelajaran</span></h1>
                <p class="text-gray-600">Akun kamu terdaftar di <strong class="text-indigo-600">{{ $student->classRoom?->name ?? '-' }}</strong> • <span class="text-gray-500">{{ $student->classRoom?->school?->name ?? 'Sekolah belum diatur' }}</span></p>
            </div>

            <!-- Filters Section -->
            <div class="bg-white rounded-md shadow-sm p-6 mb-6">
                <h3 class="font-semibold text-lg mb-4 text-gray-900">Filter</h3>

                <!-- Grade Filter Buttons -->
                <div class="mb-6">
                    <p class="text-sm font-medium text-gray-700 mb-3">Pilih Kelas:</p>
                    <div class="flex gap-2 flex-wrap">
                        <a href="{{ route('lessons.student') }}" 
                           class="inline-flex items-center px-4 py-2 rounded-lg font-medium transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-300 {{ !request('grade') ? 'bg-indigo-600 text-white shadow-sm border border-indigo-600' : 'bg-white border border-slate-200 text-slate-600 hover:border-indigo-300 hover:text-indigo-600' }}">
                            Semua
                        </a>
                        <a href="{{ route('lessons.student', ['grade' => '10']) }}" 
                           class="inline-flex items-center px-4 py-2 rounded-lg font-medium transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-300 {{ request('grade') == '10' ? 'bg-indigo-600 text-white shadow-sm border border-indigo-600' : 'bg-white border border-slate-200 text-slate-600 hover:border-indigo-300 hover:text-indigo-600' }}">
                            Kelas 10
                        </a>
                        <a href="{{ route('lessons.student', ['grade' => '11']) }}" 
                           class="inline-flex items-center px-4 py-2 rounded-lg font-medium transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-300 {{ request('grade') == '11' ? 'bg-indigo-600 text-white shadow-sm border border-indigo-600' : 'bg-white border border-slate-200 text-slate-600 hover:border-indigo-300 hover:text-indigo-600' }}">
                            Kelas 11
                        </a>
                        <a href="{{ route('lessons.student', ['grade' => '12']) }}" 
                           class="inline-flex items-center px-4 py-2 rounded-lg font-medium transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-300 {{ request('grade') == '12' ? 'bg-indigo-600 text-white shadow-sm border border-indigo-600' : 'bg-white border border-slate-200 text-slate-600 hover:border-indigo-300 hover:text-indigo-600' }}">
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

                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-300">
                        Cari
                    </button>
                    <a href="{{ request('grade') ? route('lessons.student', ['grade' => request('grade')]) : route('lessons.student') }}" 
                       class="px-4 py-2 rounded-lg font-medium transition bg-white border border-slate-200 text-slate-700 hover:border-indigo-300 hover:text-indigo-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-300">
                        Reset
                    </a>
                </form>
            </div>

            <!-- Schedule Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($lessons as $lesson)
                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition overflow-hidden card-hover">
                        <div class="px-5 py-4">
                            <div class="flex items-start justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $lesson->subject?->name ?? 'N/A' }}</h3>
                                <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 text-indigo-600 text-sm font-semibold">
                                    {{ \Carbon\Carbon::parse($lesson->date)->format('d') }}
                                </span>
                            </div>
                            
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
                                            <span class="text-slate-400">Belum diisi</span>
                                        @endif
                                    </p>
                                </div>

                                <div class="pt-2 border-t border-gray-200">
                                    <p class="text-gray-500 text-xs font-medium uppercase">Kelas</p>
                                    <p class="text-gray-900 font-medium">{{ $lesson->classRoom?->name ?? '-' }}</p>
                                </div>

                                <div>
                                    <p class="text-gray-500 text-xs font-medium uppercase">Sekolah</p>
                                    <p class="text-gray-900 font-medium">{{ $lesson->classRoom?->school?->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white border border-slate-200 rounded-xl shadow-sm p-12 text-center">
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
