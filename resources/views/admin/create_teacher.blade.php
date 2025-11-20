<x-admin-layout title="Tambah Guru">
  <div class="max-w-2xl mx-auto py-10">
      <h2 class="text-2xl font-bold mb-6">Tambah Guru</h2>

      <form action="{{ route('admin.teachers.store') }}" method="POST" class="bg-white shadow rounded-lg p-6">
          @csrf
          <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Nama</label>
              <input type="text" name="name" required class="mt-1 block w-full border rounded px-3 py-2">
          </div>
          <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Email</label>
              <input type="email" name="email" required class="mt-1 block w-full border rounded px-3 py-2">
          </div>
          <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
              <input type="text" name="phone" value="{{ old('phone') }}" placeholder="0812xxxx" class="mt-1 block w-full border rounded px-3 py-2">
          </div>
          <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Password</label>
              <input type="password" name="password" required class="mt-1 block w-full border rounded px-3 py-2">
          </div>
          <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
              <input type="password" name="password_confirmation" required class="mt-1 block w-full border rounded px-3 py-2">
          </div>
          <div>
              <button class="px-4 py-2 bg-[#2E529F] text-white rounded">Simpan</button>
          </div>
      </form>
  </div>
</x-admin-layout>
