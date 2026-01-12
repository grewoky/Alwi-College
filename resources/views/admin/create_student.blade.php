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
              <select name="class_room_id" id="classRoomSelect" class="mt-1 block w-full border px-3 py-2 rounded select2-classroom">
                  <option value="">-- Pilih Kelas --</option>
                  @foreach($classRooms as $c)
                      <option value="{{ $c->id }}" @selected(old('class_room_id') == $c->id)>{{ $c->grade }} - {{ $c->name }} ({{ $c->school->name ?? '' }})</option>
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

          <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">NIS </label>
              <input type="text" name="nis" value="{{ old('nis') }}" class="mt-1 block w-full border px-3 py-2 rounded" />
              @error('nis') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>

          <div class="mb-4">
              <label class="flex items-center gap-3">
                  <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{ old('is_active', true) ? 'checked' : '' }}>
                  <span class="text-sm font-medium text-gray-700">Akun Aktif</span>
              </label>
              <p class="text-xs text-gray-500 mt-1">Jika dicentang, siswa dapat melakukan login. Jika tidak, akun tidak bisa login.</p>
          </div>

          <div class="flex justify-end gap-2">
              <a href="{{ route('admin.students.index') }}" class="px-4 py-2 border rounded">Batal</a>
              <button class="px-4 py-2 bg-indigo-600 text-white rounded">Simpan</button>
          </div>
      </form>
  </div>
</x-admin-layout>
