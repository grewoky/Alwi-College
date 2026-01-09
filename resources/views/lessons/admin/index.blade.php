<x-admin-layout>
    <x-slot name="title">Jadwal Pelajaran • Admin</x-slot>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Link -->
            <div class="mb-6">
                <a href="{{ route('lessons.admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-200 bg-white text-slate-700 font-medium shadow-sm">
                    <span class="text-lg">←</span>
                    <span>Kembali ke Dashboard Jadwal</span>
                </a>
            </div>
            
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Manajemen Jadwal Pelajaran</h1>
                <p class="text-gray-600">Lihat dan kelola jadwal pelajaran semua kelas</p>
            </div>

            <!-- Action Buttons -->
            <div class="mb-6 flex flex-wrap gap-3">
                <a href="{{ route('lessons.generate.form') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md font-medium">
                    Tambah Jadwal
                </a>
                <a href="{{ route('lessons.logs.deleted') }}" class="px-4 py-2 bg-red-500 text-white rounded-md font-medium">
                    Log Terhapus
                </a>
                <a href="{{ route('lessons.logs.expired') }}" class="px-4 py-2 bg-red-400 text-white rounded-md font-medium">
                    Log Kadaluarsa
                </a>
            </div>

            <!-- Filters Section -->
            <div class="bg-white rounded-md shadow-sm p-6 mb-6">
                <h3 class="font-semibold text-lg mb-4 text-gray-900">Filter</h3>
                <form method="GET" action="{{ route('lessons.admin') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Teacher Filter -->
                    <div>
                        <label for="teacher_filter" class="block text-sm font-medium text-gray-700 mb-1">Pengajar</label>
                        <select id="teacher_filter" name="teacher_id" class="w-full px-3 py-2 rounded-md border border-gray-300 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent select2-teacher">
                            <option value="">-- Semua --</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Filter -->
                    <div>
                        <label for="date_filter" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input id="date_filter" type="date" name="date" value="{{ request('date') }}" 
                               class="w-full px-3 py-2 rounded-md border border-gray-300 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Sort -->
                    <div>
                        <label for="sort_filter" class="block text-sm font-medium text-gray-700 mb-1">Urutkan</label>
                        <select id="sort_filter" name="sort" class="w-full px-3 py-2 rounded-md border border-gray-300 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="date_desc" {{ request('sort', 'date_desc') === 'date_desc' ? 'selected' : '' }}>Tanggal terbaru</option>
                            <option value="teacher_asc" {{ request('sort') === 'teacher_asc' ? 'selected' : '' }}>Nama pengajar (A-Z)</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2 items-end">
                        <button type="submit" class="flex-1 px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium transition">
                            Cari
                        </button>
                        <a href="{{ route('lessons.admin') }}" class="flex-1 px-3 py-2 bg-gray-300 text-gray-900 rounded-md hover:bg-gray-400 font-medium text-center transition">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Schedule Table -->
            <div class="bg-white rounded-md shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-[900px] w-full">
                        <thead>
                            <tr class="bg-gray-100 border-b border-gray-200">
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Tanggal</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Mata Pelajaran</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Sekolah</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Kelas</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Pengajar</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Jam</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lessons as $lesson)
                                <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($lesson->date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                        {{ $lesson->subject?->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium">{{ $lesson->classRoom?->school?->name ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $lesson->classRoom->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $lesson->teacher->user->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        @if($lesson->start_time && $lesson->end_time)
                                            {{ $lesson->start_time }} - {{ $lesson->end_time }}
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('lessons.edit', $lesson) }}" 
                                               class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm font-medium transition">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('lessons.destroy', $lesson) }}" 
                                                  style="display: inline;"
                                                  onsubmit="return confirm('Hapus jadwal ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-sm font-medium transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        <p class="font-medium">Belum ada jadwal pelajaran</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($lessons->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        {{ $lessons->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

</x-admin-layout>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for teacher filter
    $('#teacher_filter').select2({
      theme: 'bootstrap-5',
      width: '100%',
      allowClear: true,
      placeholder: 'Cari pengajar...',
      language: 'id',
      matcher: customMatcher,
      templateSelection: formatSelection,
      templateResult: formatResult,
    });

    function customMatcher(params, data) {
      if ($.trim(params.term) === '') {
        return data;
      }
      var term = params.term.toLowerCase();
      var text = data.text.toLowerCase();
      if (text.indexOf(term) > -1) {
        return $.extend({}, data, { highlighted: true });
      }
      return null;
    }

    function formatSelection(data) {
      if (!data.id) return data.text;
      return data.text;
    }

    function formatResult(data) {
      if (!data.id) return data.text;
      return $('<div class="py-2">' + data.text + '</div>');
    }
  });
</script>

<style>
  .select2-container--bootstrap-5 .select2-selection--single {
    height: 38px;
    border: 1px solid #d1d5db !important;
    border-radius: 0.375rem;
    padding-top: 0.35rem;
  }

  .select2-container--bootstrap-5.select2-container--open .select2-selection--single {
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
  }

  .select2-container--bootstrap-5 .select2-dropdown {
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    margin-top: 2px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  }

  .select2-results__option {
    padding: 8px 12px;
  }

  .select2-results__option--highlighted[aria-selected] {
    background-color: #3b82f6;
    color: white;
  }
</style>
