<x-admin-layout title="Tambah Guru">
  <div class="max-w-2xl mx-auto py-10">
      <h2 class="text-2xl font-bold mb-6">Tambah Guru</h2>

            <form action="{{ route('admin.teachers.store') }}" method="POST" class="bg-white shadow rounded-lg p-6">
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
                            <input type="text" name="name" required value="{{ old('name') }}" class="mt-1 block w-full border rounded px-3 py-2">
                            @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
          <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" required value="{{ old('email') }}" class="mt-1 block w-full border rounded px-3 py-2">
                            @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>
          <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="0812xxxx" class="mt-1 block w-full border rounded px-3 py-2">
                            @error('phone') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>
          <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" required class="mt-1 block w-full border rounded px-3 py-2">
                            @error('password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>
          <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" required class="mt-1 block w-full border rounded px-3 py-2">
                            @error('password_confirmation') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>
          <div class="mb-4">
              <label class="flex items-center gap-3">
                  <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{ old('is_active', true) ? 'checked' : '' }}>
                  <span class="text-sm font-medium text-gray-700">Akun Aktif</span>
              </label>
              <p class="text-xs text-gray-500 mt-1">Jika dicentang, guru dapat melakukan login. Jika tidak, akun tidak bisa login.</p>
          </div>
          <div>
              <button class="px-4 py-2 bg-[#2E529F] text-white rounded">Simpan</button>
          </div>
      </form>
  </div>
</x-admin-layout>
