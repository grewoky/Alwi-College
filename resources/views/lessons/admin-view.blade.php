<x-admin-layout>
    <x-slot name="title">Jadwal Les • Admin</x-slot>

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">📅 Manajemen Jadwal Pelajaran</h1>
                <p class="text-gray-600">Lihat dan kelola jadwal pelajaran semua kelas</p>
            </div>

            <!-- Action Buttons -->
            <div class="mb-6 flex flex-wrap gap-3">
                <a href="{{ route('lessons.generate.form') }}" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-bold transition">
                    ➕ Generate Jadwal Baru
                </a>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="font-bold text-lg mb-4">🔍 Filter Jadwal</h3>
                <form method="GET" action="{{ route('lessons.admin') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pengajar</label>
                        <select name="teacher_id" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-600">
                            <option value="">-- Semua Pengajar --</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                        <select name="class_room_id" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-600">
                            <option value="">-- Semua Kelas --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ request('class_room_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                        <input type="date" name="date" value="{{ request('date') }}" 
                               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-600">
                    </div>

                    <button type="submit" class="md:col-span-1 md:mt-auto px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold">
                        🔍 Filter
                    </button>
                    <a href="{{ route('lessons.admin') }}" class="md:col-span-1 md:mt-auto px-6 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 font-bold text-center">
                        ⟲ Reset
                    </a>
                </form>
            </div>

            <!-- Schedule Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-800 text-white">
                    <h2 class="text-xl font-bold">📋 Daftar Jadwal Pelajaran</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-100 border-b">
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Mata Pelajaran</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Kelas</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Pengajar</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Jam</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lessons as $lesson)
                                <tr class="border-b hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-sm text-gray-800">
                                        {{ \Carbon\Carbon::parse($lesson->date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                        {{ $lesson->subject?->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-800">
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full font-semibold">
                                            {{ $lesson->classRoom->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-800">
                                        {{ $lesson->teacher->user->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-800">
                                        @if($lesson->start_time && $lesson->end_time)
                                            {{ $lesson->start_time }} - {{ $lesson->end_time }}
                                        @else
                                            <span class="text-gray-400">Belum ditentukan</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm space-x-2 flex justify-center gap-2">
                                        <a href="{{ route('lessons.edit', $lesson) }}" class="px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded font-semibold transition">
                                            ✏️ Edit
                                        </a>
                                        <form method="POST" action="{{ route('lessons.destroy', $lesson) }}" style="display: inline;" onsubmit="return confirm('Hapus jadwal ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded font-semibold transition">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        <p class="text-lg">📭 Belum ada jadwal pelajaran</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($lessons->hasPages())
                    <div class="px-6 py-4 border-t bg-gray-50">
                        {{ $lessons->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

</x-admin-layout>
