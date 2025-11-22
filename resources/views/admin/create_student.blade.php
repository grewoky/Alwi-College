<x-admin-layout title="Tambah Siswa">
  <div class="max-w-3xl mx-auto py-10">
      <h2 class="text-2xl font-bold mb-6">Tambah Siswa</h2>

      <form method="POST" action="{{ route('admin.students.store') }}" class="bg-white p-6 rounded shadow">
          @csrf

          <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Nama</label>
              <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full border px-3 py-2 rounded" />
          </div>

          <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Email</label>
              <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full border px-3 py-2 rounded" />
          </div>

          <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
              <input type="text" name="phone" value="{{ old('phone') }}" placeholder="0812xxxx" class="mt-1 block w-full border px-3 py-2 rounded" />
          </div>

          <div class="mb-4 grid grid-cols-2 gap-4">
              <div>
                  <label class="block text-sm font-medium text-gray-700">Password</label>
                  <input type="password" name="password" required class="mt-1 block w-full border px-3 py-2 rounded" />
              </div>
              <div>
                  <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                  <input type="password" name="password_confirmation" required class="mt-1 block w-full border px-3 py-2 rounded" />
              </div>
          </div>

          <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Kelas </label>
              <select name="class_room_id" class="mt-1 block w-full border px-3 py-2 rounded">
                  <option value="">-- Pilih Kelas --</option>
                  @foreach($classRooms as $c)
                      <option value="{{ $c->id }}">{{ $c->display_label }}</option>
                  @endforeach
              </select>
          </div>

          <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">NIS </label>
              <input type="text" name="nis" value="{{ old('nis') }}" class="mt-1 block w-full border px-3 py-2 rounded" />
          </div>

          <div class="flex justify-end gap-2">
              <a href="{{ route('admin.students.index') }}" class="px-4 py-2 border rounded">Batal</a>
              <button class="px-4 py-2 bg-indigo-600 text-white rounded">Simpan</button>
          </div>
      </form>
  </div>
</x-admin-layout>
