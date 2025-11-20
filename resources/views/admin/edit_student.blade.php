<x-admin-layout title="Edit Siswa">
  <div class="max-w-3xl mx-auto py-10">
    <h2 class="text-2xl font-bold mb-4">Edit Siswa</h2>

    @if($errors->any())
      <div class="mb-4 text-red-700">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.students.update', $student->id) }}" class="bg-white p-6 rounded shadow">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="block text-sm font-medium">Nama</label>
        <input type="text" name="name" value="{{ old('name', $student->user->name ?? '') }}" class="border p-2 w-full rounded" required>
      </div>

      <div class="mb-3">
        <label class="block text-sm font-medium">Email</label>
        <input type="email" name="email" value="{{ old('email', $student->user->email ?? '') }}" class="border p-2 w-full rounded" required>
      </div>

      <div class="mb-3">
        <label class="block text-sm font-medium">Nomor Telepon</label>
        <input type="text" name="phone" value="{{ old('phone', $student->user->phone ?? '') }}" class="border p-2 w-full rounded">
      </div>

      <div class="mb-3">
        <label class="block text-sm font-medium">Kelas</label>
        <select name="class_room_id" class="border p-2 w-full rounded">
          <option value="">-- Tidak Ada --</option>
          @foreach($classRooms as $c)
            <option value="{{ $c->id }}" @selected(old('class_room_id', $student->class_room_id) == $c->id)>{{ $c->grade }} - {{ $c->name }} ({{ $c->school->name ?? '-' }})</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="block text-sm font-medium">NIS</label>
        <input type="text" name="nis" value="{{ old('nis', $student->nis) }}" class="border p-2 w-full rounded">
      </div>

      <div class="mb-3">
        <label class="block text-sm font-medium">Status Aktif</label>
        <select name="is_approved" class="border p-2 rounded">
          <option value="1" @selected(old('is_approved', $student->user->is_approved ?? 1) == 1)>Aktif</option>
          <option value="0" @selected(old('is_approved', $student->user->is_approved ?? 1) == 0)>Nonaktif</option>
        </select>
        <p class="text-xs text-gray-500 mt-1">Perubahan status hanya bisa dilakukan di halaman ini.</p>
      </div>

      <div class="flex gap-2">
        <a href="{{ route('admin.students.index') }}" class="px-4 py-2 border rounded">Batal</a>
        <button class="px-4 py-2 bg-indigo-600 text-white rounded">Simpan</button>
      </div>
    </form>
  </div>
</x-admin-layout>