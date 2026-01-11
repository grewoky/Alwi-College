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
    <!-- Header with Description -->
    <div class="mb-8 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl p-8 text-white shadow-lg">
      <h1 class="text-3xl font-bold mb-3">📅 Generate Jadwal Pelajaran</h1>
      <p class="text-blue-100 mb-4">Buat jadwal pembelajaran secara otomatis untuk kelas dan guru</p>
    </div>

    <!-- Success Message -->
    @if(session('ok'))
      <div class="mb-6 bg-green-100 text-green-800 p-4 rounded-lg border border-green-300">
        ✓ {{ session('ok') }}
      </div>
    @endif

    <!-- Warning Message -->
    @if(session('warning'))
      <div class="mb-6 bg-yellow-100 text-yellow-800 p-4 rounded-lg border border-yellow-300">
        ⚠️ {{ session('warning') }}
      </div>
    @endif

    <!-- General Error Message -->
    @if($errors->has('general'))
      <div class="mb-6 bg-red-100 text-red-800 p-4 rounded-lg border border-red-300">
        ✕ {{ $errors->first('general') }}
      </div>
    @endif

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-lg p-8">
      <form method="POST" action="{{ route('lessons.generate') }}" class="space-y-6">
        @csrf

        <!-- School Selection -->
        <div>
          <label class="block text-sm font-bold text-gray-900 mb-3">🏛️ Pilih Sekolah</label>
          <select name="school" id="schoolSelect" required class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600 select2-input">
            <option value="">-- Pilih Sekolah --</option>
            @foreach(($schoolsList ?? ['Bangau','IGS','Kumbang','Negeri','Xavega']) as $schoolOption)
              <option value="{{ $schoolOption }}" {{ old('school') === $schoolOption ? 'selected' : '' }}>
                {{ $schoolOption }}
              </option>
            @endforeach
          </select>
          @error('school')
            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
          @enderror
        </div>

        <!-- Grade (Kelas 10, 11, 12) -->
        <div>
          <label class="block text-sm font-bold text-gray-900 mb-3">📚 Pilih Kelas</label>
          <select name="grade" id="gradeSelect" required class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600 select2-input">
            <option value="">-- Pilih Kelas --</option>
            <option value="10" {{ old('grade') === '10' ? 'selected' : '' }}>Kelas 10</option>
            <option value="11" {{ old('grade') === '11' ? 'selected' : '' }}>Kelas 11</option>
            <option value="12" {{ old('grade') === '12' ? 'selected' : '' }}>Kelas 12</option>
          </select>
          @error('grade')
            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
          @enderror
        </div>

        <!-- Guru -->
        <div>
          <label class="block text-sm font-bold text-gray-900 mb-3">👨‍🏫 Pilih Guru</label>
          <select name="teacher_id" id="teacherSelect" required class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600 select2-input">
            <option value="">-- Pilih Guru --</option>
            @forelse($teachersList as $tch)
              <option value="{{ $tch->id }}" {{ old('teacher_id') == $tch->id ? 'selected' : '' }}>{{ $tch->user->name }}</option>
            @empty
              <option disabled>Tidak ada guru</option>
            @endforelse
          </select>
          @error('teacher_id')
            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
          @enderror
        </div>

        <!-- Materi (Optional) -->
        <div>
          <label class="block text-sm font-bold text-gray-900 mb-3">📖 Pilih Materi (Opsional)</label>
          <select name="subject_id" id="subjectSelect" class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600 select2-input">
            <option value="">-- Tanpa Materi --</option>
            @forelse($subjectsList as $sbj)
              <option value="{{ $sbj->id }}" {{ old('subject_id') == $sbj->id ? 'selected' : '' }}>{{ $sbj->name }}</option>
            @empty
            @endforelse
          </select>
        </div>

        <!-- Description / Deskripsi Pelajaran -->
        <div>
          <label class="block text-sm font-bold text-gray-900 mb-3">📝 Deskripsi Pelajaran (Opsional)</label>
          <textarea name="description" rows="4" placeholder="Tuliskan detail pelajaran, topik yang akan diajarkan, atau informasi penting tentang kelas..." 
            class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600 resize-none">{{ old('description') }}</textarea>
          <p class="text-xs text-gray-500 mt-2">Contoh: Pembelajaran Matematika tentang Aljabar, Persiapan Ujian Nasional, Praktikum Kimia, dll</p>
          @error('description')
            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
          @enderror
        </div>

        <!-- Date Range -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-3">📅 Tanggal Mulai</label>
            <input type="date" name="start_date" required min="{{ date('Y-m-d') }}" value="{{ old('start_date') }}" id="start_date" class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600">
            @error('start_date')
              <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
            @enderror
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-3">📅 Tanggal Selesai</label>
            <input type="date" name="end_date" required min="{{ date('Y-m-d') }}" value="{{ old('end_date') }}" id="end_date" class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600">
            @error('end_date')
              <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
            @enderror
          </div>
        </div>

        <script>
          (function(){
            const start = document.getElementById('start_date');
            const end = document.getElementById('end_date');
            if (!start || !end) return;

            // Ensure end date cannot be set before start date
            start.addEventListener('change', function(){
              if (!start.value) return;
              end.min = start.value;
              // if current end is before start, clear it
              if (end.value && end.value < start.value) {
                end.value = start.value;
              }
            });

            // On page load ensure end.min respects start or today
            document.addEventListener('DOMContentLoaded', function(){
              const today = new Date().toISOString().split('T')[0];
              if (!start.value) {
                end.min = today;
              } else {
                end.min = start.value;
              }
            });

            // Extra safety: prevent form submit if dates invalid
            const form = start.closest('form');
            if (form) {
              form.addEventListener('submit', function(e){
                if (start.value && end.value && end.value < start.value) {
                  e.preventDefault();
                  alert('Tanggal selesai tidak boleh sebelum tanggal mulai.');
                  end.focus();
                }
              });
            }
          })();
        </script>

        <!-- Time Range -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-3">🕐 Jam Mulai</label>
            <select id="start_time" name="start_time" class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600">
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
                <option value="{{ $time }}">
                  {{ $time }} WIB
                </option>
              @endforeach
            </select>
            @error('start_time')
              <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
            @enderror
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-3">🕐 Jam Selesai</label>
            <select id="end_time" name="end_time" class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600">
              <option value="">-- Pilih Jam Selesai --</option>
              @foreach($times as $time)
                <option value="{{ $time }}">
                  {{ $time }} WIB
                </option>
              @endforeach
            </select>
            @error('end_time')
              <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
            @enderror
          </div>
        </div>

        <!-- Submit -->
        <!-- Option: generate per-variant classes -->
        <div class="flex items-center gap-3">
          <input type="checkbox" id="per_variant" name="per_variant" {{ old('per_variant') ? 'checked' : '' }} class="h-4 w-4">
          <label for="per_variant" class="text-sm text-gray-700">Buat untuk tiap varian kelas (IPA,IPS). Jika tidak dicentang, jadwal dibuat sekali per grade.</label>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg flex items-center justify-center gap-2">
          🚀 Generate Jadwal Setiap Hari
        </button>
      </form>
    </div>
  </div>
</div>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for all select fields with class select2-input
    const select2Elements = document.querySelectorAll('.select2-input');
    
    select2Elements.forEach(function(element) {
      $(element).select2({
        theme: 'bootstrap-5',
        width: '100%',
        allowClear: true,
        placeholder: element.getAttribute('data-placeholder') || element.querySelector('option:first')?.textContent,
        language: 'id',
        matcher: customMatcher,
        templateSelection: formatSelection,
        templateResult: formatResult,
      });
    });

    // Custom matcher for better search
    function customMatcher(params, data) {
      if ($.trim(params.term) === '') {
        return data;
      }

      var term = params.term.toLowerCase();
      var text = data.text.toLowerCase();

      // Check if text contains the search term
      if (text.indexOf(term) > -1) {
        return $.extend({}, data, {
          highlighted: true
        });
      }

      return null;
    }

    // Format selection to show text clearly
    function formatSelection(data) {
      if (!data.id) return data.text;
      return data.text;
    }

    // Format result in dropdown
    function formatResult(data) {
      if (!data.id) return data.text;
      return $('<div class="py-2">' + data.text + '</div>');
    }

    // Preserve selected values on form validation errors
    @if(old('school'))
      $('#schoolSelect').val('{{ old("school") }}').trigger('change');
    @endif

    @if(old('teacher_id'))
      $('#teacherSelect').val('{{ old("teacher_id") }}').trigger('change');
    @endif

    @if(old('subject_id'))
      $('#subjectSelect').val('{{ old("subject_id") }}').trigger('change');
    @endif
  });
</script>

<style>
  /* Custom Select2 Styling */
  .select2-container--bootstrap-5 .select2-selection--single {
    height: 42px;
    border: 2px solid #d1d5db;
    border-radius: 0.5rem;
    padding-top: 0.5rem;
    transition: all 0.3s ease;
  }

  .select2-container--bootstrap-5.select2-container--open .select2-selection--single {
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
  }

  .select2-container--bootstrap-5 .select2-selection--single:focus,
  .select2-container--bootstrap-5 .select2-selection--single:focus-visible {
    border-color: #2563eb;
    outline: none;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
  }

  .select2-container--bootstrap-5 .select2-search__field {
    padding: 6px 8px;
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

  .select2-results__option--highlighted[aria-selected],
  .select2-results__option:hover {
    background-color: #3b82f6;
    color: white;
  }

  .select2-container--bootstrap-5 .select2-results__option[aria-selected=true] {
    background-color: #dbeafe;
    color: #1e40af;
  }

  /* Clear button styling */
  .select2-container--bootstrap-5 .select2-selection__clear {
    color: #6b7280;
    font-size: 14px;
  }

  .select2-container--bootstrap-5 .select2-selection__clear:hover {
    color: #1f2937;
  }
</style>

</x-admin-layout>
