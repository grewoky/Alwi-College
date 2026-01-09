<x-admin-layout>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<div class="min-h-screen bg-gray-50 py-12">
  <div class="max-w-2xl mx-auto px-4">
    <div class="mb-6">
      <a href="{{ route('lessons.admin') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-200 bg-white text-slate-700 font-medium shadow-sm">
        <span class="text-lg">←</span>
        <span>Kembali ke Manajemen Jadwal</span>
      </a>
    </div>
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-2">✏️ Edit Jadwal Pelajaran</h1>
      <p class="text-gray-600">Tanggal: <strong>{{ $lesson->date }}</strong> | Kelas: <strong>{{ $lesson->classRoom->name }}</strong> | Guru: <strong>{{ $lesson->teacher->user->name }}</strong></p>
    </div>

    <!-- Success Message -->
    @if(session('ok'))
      <div class="mb-6 bg-green-100 text-green-800 p-4 rounded-lg border border-green-300">
        ✓ {{ session('ok') }}
      </div>
    @endif

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-lg p-8">
      <form method="POST" action="{{ route('lessons.update', $lesson) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Materi -->
        <div>
          <label class="block text-sm font-bold text-gray-900 mb-3">Pilih Materi</label>
          <select name="subject_id" id="subjectSelect" class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600 select2-input">
            <option value="">-- Tanpa Materi --</option>
            @forelse($subjectsList as $sbj)
              <option value="{{ $sbj->id }}" @if($lesson->subject_id == $sbj->id) selected @endif>
                {{ $sbj->name }}
              </option>
            @empty
            @endforelse
          </select>
          @error('subject_id')
            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
          @enderror
        </div>

        <!-- Time Range -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-3">Jam Mulai</label>
            <select name="start_time" id="start_time" class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600 select2-input">
              <option value="">-- Pilih Jam Mulai --</option>
              @php
                $times = [];
                for ($hour = 9; $hour < 20; $hour++) {
                  $times[] = sprintf("%02d:00", $hour);
                  $times[] = sprintf("%02d:30", $hour);
                }
                $times[] = "20:00";
              @endphp
              @foreach($times as $time)
                <option value="{{ $time }}" @if($lesson->start_time == $time) selected @endif>
                  {{ $time }} WIB
                </option>
              @endforeach
            </select>
            @error('start_time')
              <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
            @enderror
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-3">Jam Selesai</label>
            <select name="end_time" id="end_time" class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600 select2-input">
              <option value="">-- Pilih Jam Selesai --</option>
              @foreach($times as $time)
                <option value="{{ $time }}" @if($lesson->end_time == $time) selected @endif>
                  {{ $time }} WIB
                </option>
              @endforeach
            </select>
            @error('end_time')
              <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
            @enderror
          </div>
        </div>

        <!-- Description (Deskripsi Pelajaran) -->
        <div>
          <label class="block text-sm font-bold text-gray-900 mb-3">Deskripsi Pelajaran (Opsional)</label>
          <textarea name="description" rows="3" placeholder="Tuliskan catatan atau deskripsi pelajaran..." 
            class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600 resize-none">{{ old('description', $lesson->description ?? '') }}</textarea>
          @error('description')
            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
          @enderror
        </div>

        <!-- Buttons -->
        <div class="flex gap-3">
          <button type="submit" class="flex-1 bg-blue-600 text-white font-bold py-3 rounded-lg">
            💾 Simpan Perubahan
          </button>
          <a href="{{ route('lessons.admin') }}" class="flex-1 bg-slate-500 text-white font-bold py-3 rounded-lg text-center">
            ← Kembali
          </a>
        </div>
      </form>

      <!-- Delete Button -->
      <div class="mt-8 pt-8 border-t">
        <form method="POST" action="{{ route('lessons.destroy', $lesson) }}" onsubmit="return confirm('Hapus jadwal ini?');" class="inline">
          @csrf
          @method('DELETE')
          <button type="submit" class="bg-red-600 text-white font-bold py-2 px-4 rounded-lg">
            🗑️ Hapus Jadwal
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
</x-admin-layout>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for subject select
    $('.select2-input').select2({
      theme: 'bootstrap-5',
      width: '100%',
      allowClear: true,
      placeholder: 'Cari materi...',
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
    height: 44px;
    border: 2px solid #d1d5db;
    border-radius: 0.5rem;
    padding-top: 0.5rem;
    transition: all 0.3s ease;
  }

  .select2-container--bootstrap-5.select2-container--open .select2-selection--single {
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
  }

  .select2-container--bootstrap-5 .select2-dropdown {
    border: 2px solid #d1d5db;
    border-radius: 0.5rem;
    margin-top: 4px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  }

  .select2-results__option {
    padding: 10px 12px;
  }

  .select2-results__option--highlighted[aria-selected] {
    background-color: #3b82f6;
    color: white;
  }
</style>
