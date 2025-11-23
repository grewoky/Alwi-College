<x-admin-layout title="Tambah Siswa">
  <div class="max-w-3xl mx-auto py-10">
      <h2 class="text-2xl font-bold mb-6">Tambah Siswa</h2>

            <form method="POST" action="{{ route('admin.students.store') }}" class="bg-white p-6 rounded shadow">
                    @csrf

                    @if($errors->any())
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 p-3 rounded">
                            <ul class="list-disc pl-5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Nama</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full border px-3 py-2 rounded" />
                            @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

          <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Email</label>
              <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full border px-3 py-2 rounded" />
              @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>

          <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
              <input type="text" name="phone" value="{{ old('phone') }}" placeholder="0812xxxx" class="mt-1 block w-full border px-3 py-2 rounded" />
              @error('phone') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>

          <div class="mb-4 grid grid-cols-2 gap-4">
              <div>
                  <label class="block text-sm font-medium text-gray-700">Password</label>
                  <input type="password" name="password" required class="mt-1 block w-full border px-3 py-2 rounded" />
                  @error('password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
              </div>
              <div>
                  <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                  <input type="password" name="password_confirmation" required class="mt-1 block w-full border px-3 py-2 rounded" />
                  @error('password_confirmation') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
              </div>
          </div>

          <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Kelas </label>
              <select name="class_room_id" class="mt-1 block w-full border px-3 py-2 rounded">
                  <option value="">-- Pilih Kelas --</option>
                  @foreach($classRooms as $c)
                      <option value="{{ $c->id }}" @selected(old('class_room_id') == $c->id)>{{ $c->grade }} - {{ $c->name }} ({{ $c->school->name ?? '' }})</option>
                  @endforeach
              </select>
              @error('class_room_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>

          <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">NIS </label>
              <input type="text" name="nis" value="{{ old('nis') }}" class="mt-1 block w-full border px-3 py-2 rounded" />
              @error('nis') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>

          <div class="flex justify-end gap-2">
              <a href="{{ route('admin.students.index') }}" class="px-4 py-2 border rounded">Batal</a>
              <button class="px-4 py-2 bg-indigo-600 text-white rounded">Simpan</button>
          </div>
      </form>
  </div>
</x-admin-layout>
