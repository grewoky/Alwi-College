<x-admin-layout>
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

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-lg p-8">
      <form method="POST" action="{{ route('lessons.generate') }}" class="space-y-6">
        @csrf

        <!-- School Selection -->
        <div>
          <label class="block text-sm font-bold text-gray-900 mb-3">🏛️ Pilih Sekolah</label>
          <select name="school" required class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600">
            <option value="">-- Pilih Sekolah --</option>
            @foreach(['Negeri','IGS','Xavega','Bangau','Kumbang'] as $schoolOption)
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
          <select name="grade" id="gradeSelect" required class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600">
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
          <select name="teacher_id" required class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600">
            <option value="">-- Pilih Guru --</option>
            @forelse($teachersList as $tch)
              <option value="{{ $tch->id }}">{{ $tch->user->name }}</option>
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
          <select name="subject_id" class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600">
            <option value="">-- Tanpa Materi --</option>
            @forelse($subjectsList as $sbj)
              <option value="{{ $sbj->id }}">{{ $sbj->name }}</option>
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
            <select name="start_time" class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600">
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
            <select name="end_time" class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600">
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

</x-admin-layout>
