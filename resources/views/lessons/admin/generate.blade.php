<x-admin-layout>
<div class="min-h-screen bg-gray-50 py-12">
  <div class="max-w-2xl mx-auto px-4">
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
            <option value="Negeri">Negeri</option>
            <option value="IGS">IGS</option>
            <option value="Xavega">Xavega</option>
            <option value="Bangau">Bangau</option>
          </select>
          @error('school')
            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
          @enderror
        </div>

        <!-- Grade (Kelas 10, 11, 12) -->
        <div>
          <label class="block text-sm font-bold text-gray-900 mb-3">📚 Pilih Kelas</label>
          <select name="grade" id="gradeSelect" required class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600">
            <option value="">-- Pilih Kelas --</option>
            <option value="10">Kelas 10</option>
            <option value="11">Kelas 11</option>
            <option value="12">Kelas 12</option>
          </select>
          @error('grade')
            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
          @enderror
        </div>

        <!-- Ruangan Manual Input (No validation) -->
        <div>
          <label class="block text-sm font-bold text-gray-900 mb-3">🏫 Input Kode Ruangan</label>
          <input type="text" name="room_code" id="roomCodeInput" placeholder="Contoh: 1B, A21, A22, B31, dll" required 
            class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600"
            value="{{ old('room_code') }}">
          @error('room_code')
            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
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
            class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600 resize-none"
            value="{{ old('description') }}"></textarea>
          <p class="text-xs text-gray-500 mt-2">Contoh: Pembelajaran Matematika tentang Aljabar, Persiapan Ujian Nasional, Praktikum Kimia, dll</p>
          @error('description')
            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
          @enderror
        </div>

        <!-- Date Range -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-3">📅 Tanggal Mulai</label>
            <input type="date" name="start_date" required class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600">
            @error('start_date')
              <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
            @enderror
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-3">📅 Tanggal Selesai</label>
            <input type="date" name="end_date" required class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600">
            @error('end_date')
              <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
            @enderror
          </div>
        </div>

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
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition flex items-center justify-center gap-2">
          🚀 Generate Jadwal Setiap Hari
        </button>
      </form>
    </div>
  </div>
</div>

</x-admin-layout>
