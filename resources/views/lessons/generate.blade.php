<x-admin-layout>
<div class="min-h-screen bg-gray-50 py-12">
  <div class="max-w-2xl mx-auto px-4">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-2">📅 Generate Jadwal Pelajaran</h1>
      <p class="text-gray-600">Buat jadwal pembelajaran setiap hari untuk kelas dan guru</p>
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

        <!-- Kelas -->
        <div>
          <label class="block text-sm font-bold text-gray-900 mb-3">Pilih Kelas</label>
          <select name="class_room_id" required class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600">
            <option value="">-- Pilih Kelas --</option>
            @forelse($classrooms as $cr)
              <option value="{{ $cr->id }}">
                {{ $cr->school->name }} - Kelas {{ $cr->grade }} {{ $cr->name }}
              </option>
            @empty
              <option disabled>Tidak ada kelas</option>
            @endforelse
          </select>
          @error('class_room_id')
            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
          @enderror
        </div>

        <!-- Guru -->
        <div>
          <label class="block text-sm font-bold text-gray-900 mb-3">Pilih Guru</label>
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
          <label class="block text-sm font-bold text-gray-900 mb-3">Pilih Materi (Opsional)</label>
          <select name="subject_id" class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600">
            <option value="">-- Tanpa Materi --</option>
            @forelse($subjectsList as $sbj)
              <option value="{{ $sbj->id }}">{{ $sbj->name }}</option>
            @empty
            @endforelse
          </select>
        </div>

        <!-- Date Range -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-3">Tanggal Mulai</label>
            <input type="date" name="start_date" required class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600">
            @error('start_date')
              <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
            @enderror
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-3">Tanggal Selesai</label>
            <input type="date" name="end_date" required class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-600 focus:ring-2 focus:ring-blue-600">
            @error('end_date')
              <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
            @enderror
          </div>
        </div>

        <!-- Submit -->
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition">
          🚀 Generate Jadwal Setiap Hari
        </button>
      </form>
    </div>
  </div>
</div>
</x-admin-layout>
