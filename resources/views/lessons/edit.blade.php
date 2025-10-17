<x-admin-layout>
<div class="min-h-screen bg-gray-50 py-12">
  <div class="max-w-2xl mx-auto px-4">
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
          <select name="subject_id" class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600">
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
            <label class="block text-sm font-bold text-gray-900 mb-3">Jam Mulai (HH:MM)</label>
            <input type="time" name="start_time" value="{{ $lesson->start_time }}" class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600">
            @error('start_time')
              <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
            @enderror
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-3">Jam Selesai (HH:MM)</label>
            <input type="time" name="end_time" value="{{ $lesson->end_time }}" class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600">
            @error('end_time')
              <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
            @enderror
          </div>
        </div>

        <!-- Buttons -->
        <div class="flex gap-3">
          <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition">
            💾 Simpan Perubahan
          </button>
          <a href="{{ route('lessons.admin') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 rounded-lg transition text-center">
            ← Kembali
          </a>
        </div>
      </form>

      <!-- Delete Button -->
      <div class="mt-8 pt-8 border-t">
        <form method="POST" action="{{ route('lessons.destroy', $lesson) }}" onsubmit="return confirm('Hapus jadwal ini?');" class="inline">
          @csrf
          @method('DELETE')
          <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition">
            🗑️ Hapus Jadwal
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
</x-admin-layout>
