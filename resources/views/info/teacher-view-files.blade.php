<x-app-layout>
    <x-slot name="title">Dokumen Siswa • Guru</x-slot>

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <style>
                .heading-inline{display:inline-block;position:relative;padding-left:.75rem}
                .heading-inline::before{content:"";position:absolute;left:0;top:55%;transform:translateY(-50%);height:.6em;width:.4em;background:linear-gradient(135deg,#16a34a,#22c55e);border-radius:.2em}
            </style>
            
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2"><span class="heading-inline">Dokumen Siswa</span></h1>
                <p class="text-gray-600">Lihat file yang diupload oleh siswa Anda</p>
            </div>

            <!-- Filters -->
            <div class="mb-6 bg-white rounded-lg shadow p-6">
                <form method="GET" action="{{ route('info.teacher.student-files') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Class Filter -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">🏫 Kelas</label>
                            <select name="class_room_id" class="w-full border-2 border-gray-300 rounded-lg p-2 focus:border-green-600 focus:ring-2 focus:ring-green-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-600">
                                <option value="">-- Semua Kelas --</option>
                                @foreach($classRooms as $class)
                                    <option value="{{ $class->id }}" @selected(request('class_room_id') == $class->id)>
                                        Kelas {{ $class->grade }} - {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Subject Filter -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">📖 Pelajaran</label>
                            <input type="text" name="subject" placeholder="Cari pelajaran..." value="{{ request('subject') }}" 
                                   class="w-full border-2 border-gray-300 rounded-lg p-2 focus:border-green-600 focus:ring-2 focus:ring-green-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-600">
                        </div>

                        <!-- Material Filter -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">📝 Materi</label>
                            <input type="text" name="material" placeholder="Cari materi..." value="{{ request('material') }}" 
                                   class="w-full border-2 border-gray-300 rounded-lg p-2 focus:border-green-600 focus:ring-2 focus:ring-green-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-600">
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-600">
                            🔍 Cari
                        </button>
                        <a href="{{ route('info.teacher.student-files') }}" class="px-6 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 font-semibold transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-500">
                            ⟲ Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Empty State -->
            @if($files->isEmpty())
                <div class="bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-600 text-lg font-medium">Belum ada file</p>
                    <p class="text-gray-500 text-sm mt-1">Siswa belum mengunggah file apapun</p>
                </div>
            @else
                <!-- Files Grid (Same format as Admin) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-1 lg:grid-cols-1 xl:grid-cols-2 2xl:grid-cols-2 gap-8">
                @foreach($files as $file)
                    <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition h-full flex flex-col">
                        <!-- Header Row -->
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div class="flex items-center gap-3 flex-1">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 012-2h6a1 1 0 01.707.293l6 6a1 1 0 01.293.707v8a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"></path>
                                    </svg>
                                </div>
                                <div class="leading-snug min-w-0">
                                    <h3 class="text-lg font-semibold text-gray-900 whitespace-normal break-words">{{ $file->title }}</h3>
                                    <p class="text-sm text-gray-600 mt-0.5">{{ $file->created_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                            <!-- Download Button -->
                            <a href="{{ route('info.teacher.download', $file) }}" 
                               class="px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg text-sm font-medium transition flex-shrink-0"
                               title="Download File">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                            </a>
                        </div>

                        <!-- Student Info -->
                        <div class="mb-4 pb-4 border-b border-gray-200">
                            <div class="text-sm text-gray-600">
                                <span class="font-semibold text-gray-900">{{ $file->student->user->name ?? '-' }}</span>
                                @if($file->student->classRoom)
                                    <span class="text-gray-500"> • {{ $file->student->classRoom->name ?? 'Kelas tidak tersedia' }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Details Grid (Same as Admin) -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-auto">
                            @if($file->school)
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <p class="text-xs font-semibold text-gray-500 uppercase">Sekolah</p>
                                    <p class="text-sm font-medium text-gray-900 whitespace-normal break-words">{{ $file->school }}</p>
                                </div>
                            @else
                                <div class="bg-gray-50 p-3 rounded-lg opacity-50">
                                    <p class="text-xs font-semibold text-gray-500 uppercase">Sekolah</p>
                                    <p class="text-sm text-gray-400">-</p>
                                </div>
                            @endif

                            @if($file->class_name)
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <p class="text-xs font-semibold text-gray-500 uppercase">Kelas</p>
                                    <p class="text-sm font-medium text-gray-900 whitespace-normal break-words">{{ $file->class_name }}</p>
                                </div>
                            @else
                                <div class="bg-gray-50 p-3 rounded-lg opacity-50">
                                    <p class="text-xs font-semibold text-gray-500 uppercase">Kelas</p>
                                    <p class="text-sm text-gray-400">-</p>
                                </div>
                            @endif

                            @if($file->subject)
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <p class="text-xs font-semibold text-gray-500 uppercase">Pelajaran</p>
                                    <p class="text-sm font-medium text-gray-900 whitespace-normal break-words">{{ $file->subject }}</p>
                                </div>
                            @else
                                <div class="bg-gray-50 p-3 rounded-lg opacity-50">
                                    <p class="text-xs font-semibold text-gray-500 uppercase">Pelajaran</p>
                                    <p class="text-sm text-gray-400">-</p>
                                </div>
                            @endif

                            @if($file->material)
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <p class="text-xs font-semibold text-gray-500 uppercase">Materi</p>
                                    <p class="text-sm font-medium text-gray-900 whitespace-normal break-words">{{ $file->material }}</p>
                                </div>
                            @else
                                <div class="bg-gray-50 p-3 rounded-lg opacity-50">
                                    <p class="text-xs font-semibold text-gray-500 uppercase">Materi</p>
                                    <p class="text-sm text-gray-400">-</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
                </div>

                <!-- Pagination -->
                @if(method_exists($files, 'hasPages') && $files->hasPages())
                    <div class="mt-8">
                        {{ $files->links() }}
                    </div>
                @endif
            @endif

        </div>
    </div>
</x-app-layout>