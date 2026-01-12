<x-admin-layout title="Edit Siswa">
  <div class="max-w-3xl mx-auto py-10">
    <h2 class="text-2xl font-bold mb-4">Edit Siswa</h2>

    @if($errors->any())
      <div class="mb-4 bg-red-50 border border-red-200 text-red-700 p-3 rounded">
        <ul class="list-disc pl-5">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.students.update', $student->id) }}" class="bg-white p-6 rounded shadow">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="block text-sm font-medium">Nama</label>
        <input type="text" name="name" value="{{ old('name', $student->user?->name ?? '') }}" class="border p-2 w-full rounded" required>
        @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
      </div>

      <div class="mb-3">
        <label class="block text-sm font-medium">Email</label>
        <input type="email" name="email" value="{{ old('email', $student->user?->email ?? '') }}" class="border p-2 w-full rounded" required>
        @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
      </div>

      <div class="mb-3">
        <label class="block text-sm font-medium">Nomor Telepon</label>
        <input type="text" name="phone" value="{{ old('phone', $student->user?->phone ?? '') }}" class="border p-2 w-full rounded">
        @error('phone') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
      </div>

      <div class="mb-3">
        <label class="block text-sm font-medium">Kelas</label>
        <select name="class_room_id" id="classRoomSelect" class="border p-2 w-full rounded select2-classroom">
          <option value="">-- Tidak Ada --</option>
          @foreach($classRooms as $c)
            <option value="{{ $c->id }}" @selected(old('class_room_id', $student->class_room_id) == $c->id)>{{ $c->grade }} - {{ $c->name }} ({{ $c->school->name ?? '-' }})</option>
          @endforeach
        </select>
        @error('class_room_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
      </div>

      <!-- Select2 CSS & JS -->
      <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
      <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
      <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

      <script>
        document.addEventListener('DOMContentLoaded', function() {
          $('#classRoomSelect').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Cari kelas (grade - nama)...',
            allowClear: true
          });
        });
      </script>

      <div class="mb-3">
        <label class="block text-sm font medium">NIS</label>
        <input type="text" name="nis" value="{{ old('nis', $student->nis) }}" class="border p-2 w-full rounded">
        @error('nis') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
      </div>

      <div class="mb-3">
        <label class="block text-sm font-medium">Status Verifikasi</label>
        <select name="is_approved" class="border p-2 w-full rounded">
          <option value="1" @selected(old('is_approved', $student->user?->is_approved ?? 1) == 1)>Terverifikasi</option>
          <option value="0" @selected(old('is_approved', $student->user?->is_approved ?? 1) == 0)>Belum Terverifikasi</option>
        </select>
        <p class="text-xs text-gray-500 mt-1">Status verifikasi akun oleh admin. User tidak bisa login jika belum terverifikasi.</p>
      </div>

      <div class="mb-3">
        <label class="block text-sm font-medium">Status Akun</label>
        <select name="is_active" class="border p-2 w-full rounded">
          <option value="1" @selected(old('is_active', $student->user?->is_active ?? true) == 1)>Aktif (Bisa Login)</option>
          <option value="0" @selected(old('is_active', $student->user?->is_active ?? true) == 0)>Nonaktif (Tidak Bisa Login)</option>
        </select>
        <p class="text-xs text-gray-500 mt-1">Jika nonaktif, siswa tidak dapat login meskipun password benar.</p>
      </div>

      <div class="flex gap-2">
        <a href="{{ route('admin.students.index') }}" class="px-4 py-2 border rounded">Batal</a>
        <button class="px-4 py-2 bg-indigo-600 text-white rounded">Simpan</button>
      </div>
    </form>
  </div>
</x-admin-layout>