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
                // Jam Mulai: 09:00 - 18:30 (interval 30 menit)
                $startTimes = [];
                for ($hour = 9; $hour <= 18; $hour++) {
                  $startTimes[] = sprintf("%02d:00", $hour);
                  if ($hour < 18) {
                    $startTimes[] = sprintf("%02d:30", $hour);
                  }
                }
                $startTimes[] = "18:30";
                
                // Jam Selesai: 09:00 - 20:00 (interval 30 menit)
                $endTimes = [];
                for ($hour = 9; $hour <= 20; $hour++) {
                  $endTimes[] = sprintf("%02d:00", $hour);
                  if ($hour < 20) {
                    $endTimes[] = sprintf("%02d:30", $hour);
                  }
                }
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
              @foreach($startTimes as $time)
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
    // Initialize Select2 for subject select with advanced search
    $('.select2-input').select2({
      theme: 'bootstrap-5',
      width: '100%',
      allowClear: true,
      clearButtonLabel: 'Hapus',
      placeholder: 'Ketik untuk mencari materi...',
      language: 'id',
      matcher: advancedMatcher,
      templateSelection: formatSelection,
      templateResult: formatResult,
      minimumInputLength: 0,
      maximumSelectionLength: 1,
      dropdownParent: $(document.body),
      closeOnSelect: true,
      noResults: function(params) {
        return $('<span class="text-gray-500 text-center block py-4">' +
          '<svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
          '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />' +
          '</svg>' +
          '<p class="font-semibold text-gray-700">Tidak ada hasil</p>' +
          '<p class="text-sm text-gray-500 mt-1">Coba cari dengan kata kunci lain</p>' +
          '</span>');
      }
    });

    // Advanced search matcher - lebih fleksibel
    function advancedMatcher(params, data) {
      var term = $.trim(params.term).toLowerCase();
      
      // Jika tidak ada pencarian, tampilkan semua
      if (!term) {
        return data;
      }

      // Pencarian pada text option
      var text = data.text.toLowerCase();
      
      // Pencarian partial match (cocok dengan bagian kata)
      if (text.indexOf(term) > -1) {
        return $.extend({}, data, { 
          highlighted: true,
          matched: true 
        });
      }

      // Pencarian per kata (split by space)
      var words = term.split(/\s+/);
      var textWords = text.split(/\s+/);
      var allWordsMatch = words.every(searchWord => 
        textWords.some(textWord => textWord.indexOf(searchWord) === 0)
      );

      if (allWordsMatch) {
        return $.extend({}, data, { 
          highlighted: true,
          matched: true 
        });
      }

      return null;
    }

    function formatSelection(data) {
      if (!data.id) return data.text;
      
      // Tampilkan dengan indikator visual
      var $span = $('<span>');
      $span.text(data.text);
      $span.css({
        'display': 'inline-block',
        'padding': '4px 8px',
        'border-radius': '4px',
        'background-color': '#e0e7ff',
        'color': '#3730a3'
      });
      
      return $span;
    }

    function formatResult(data) {
      if (!data.id) return data.text;
      
      var $result = $('<div class="py-2 px-3 d-flex align-items-center">');
      var $text = $('<span class="flex-grow-1">').text(data.text);
      
      if (data.matched) {
        $text.css({
          'font-weight': '500',
          'color': '#1e40af'
        });
      }
      
      $result.append($text);
      return $result;
    }

    // Tambahkan event listener untuk menampilkan indikator pencarian
    $('.select2-input').on('select2:opening', function(e) {
      var $search = $(this).data('select2').$dropdown.find('.select2-search__field');
      if ($search.length) {
        $search.css({
          'padding': '10px 12px',
          'font-size': '14px',
          'border': '2px solid #e5e7eb',
          'border-radius': '6px',
          'width': '100%'
        });
        $search.attr('placeholder', 'Cari materi (ketik minimal 1 huruf)...');
      }
    });

    // Focus styling
    $('.select2-input').on('select2:open', function(e) {
      $(this).data('select2').$dropdown.find('.select2-search__field').focus();
    });
  });
</script>

<style>
  /* Select2 Container */
  .select2-container--bootstrap-5 .select2-selection--single {
    height: 44px;
    border: 2px solid #d1d5db;
    border-radius: 0.5rem;
    padding-top: 0.5rem;
    transition: all 0.3s ease;
    background-color: #ffffff;
  }

  .select2-container--bootstrap-5.select2-container--open .select2-selection--single {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
  }

  .select2-container--bootstrap-5.select2-container--focus .select2-selection--single {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
  }

  /* Dropdown */
  .select2-container--bootstrap-5 .select2-dropdown {
    border: 2px solid #d1d5db;
    border-radius: 0.5rem;
    margin-top: 4px;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  }

  /* Search Input */
  .select2-container--bootstrap-5 .select2-search__field {
    padding: 10px 12px !important;
    font-size: 14px !important;
    border: 2px solid #e5e7eb !important;
    border-radius: 6px !important;
    margin: 8px !important;
    width: calc(100% - 16px) !important;
    transition: all 0.2s ease !important;
  }

  .select2-container--bootstrap-5 .select2-search__field:focus {
    border-color: #2563eb !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1) !important;
    outline: none !important;
  }

  /* Results Container */
  .select2-container--bootstrap-5 .select2-results {
    max-height: 300px;
  }

  /* Result Options */
  .select2-results__option {
    padding: 10px 12px;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .select2-results__option:hover {
    background-color: #f3f4f6;
  }

  .select2-results__option--highlighted[aria-selected] {
    background-color: #3b82f6;
    color: white;
  }

  .select2-results__option--highlighted[aria-selected]:hover {
    background-color: #2563eb;
  }

  /* Clear Button */
  .select2-container--bootstrap-5 .select2-selection__clear {
    cursor: pointer;
    float: right;
    font-weight: bold;
    margin-right: 8px;
  }

  /* Loading State */
  .select2-container--bootstrap-5.select2-container--loading .select2-selection--single {
    opacity: 0.6;
  }

  /* No Results Message */
  .select2-results__message {
    padding: 10px 12px;
    color: #6b7280;
    font-size: 14px;
  }

  /* Selected Item Badge */
  .select2-selection__rendered {
    padding-left: 8px !important;
  }
</style>
