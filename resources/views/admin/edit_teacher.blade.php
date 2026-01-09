<x-admin-layout title="Edit Pengajar">
  <div class="max-w-2xl mx-auto py-10">
      <h2 class="text-2xl font-bold mb-6">Edit Pengajar</h2>

            <form action="{{ route('admin.teachers.update', $teacher) }}" method="POST" class="bg-white shadow rounded-lg p-6">
                    @csrf
                    @method('PUT')

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
                            <input type="text" name="name" required value="{{ old('name', $teacher->user->name ?? '') }}" class="mt-1 block w-full border rounded px-3 py-2">
                            @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" required value="{{ old('email', $teacher->user->email ?? '') }}" class="mt-1 block w-full border rounded px-3 py-2">
                            @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone', $teacher->user->phone ?? '') }}" class="mt-1 block w-full border rounded px-3 py-2">
                            @error('phone') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

          <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Kode Pegawai </label>
              <input type="text" name="employee_code" value="{{ old('employee_code', $teacher->employee_code ?? '') }}" class="mt-1 block w-full border rounded px-3 py-2">
          </div>

          <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Status Aktif</label>
              <select name="is_approved" class="mt-1 block w-full border rounded px-3 py-2">
                  <option value="1" @selected(old('is_approved', $teacher->user->is_approved ?? 1) == 1)>Aktif</option>
                  <option value="0" @selected(old('is_approved', $teacher->user->is_approved ?? 1) == 0)>Nonaktif</option>
              </select>
              <p class="text-xs text-gray-500 mt-1">Status verifikasi dari admin (belum memperhatikan akses login).</p>
          </div>

          <div class="mb-4">
              <label class="flex items-center gap-3">
                  <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{ old('is_active', $teacher->user->is_active ?? true) ? 'checked' : '' }}>
                  <span class="text-sm font-medium text-gray-700">Akun Dapat Login</span>
              </label>
              <p class="text-xs text-gray-500 mt-1">Jika dicentang, guru dapat melakukan login. Jika tidak, akun tidak bisa login meskipun password benar.</p>
          </div>

          <div class="flex gap-2">
              <button class="px-4 py-2 bg-[#2E529F] text-white rounded">Simpan</button>
              <a href="{{ route('admin.teachers.index') }}" class="px-4 py-2 bg-gray-200 rounded">Batal</a>
          </div>
      </form>
  </div>
</x-admin-layout>
