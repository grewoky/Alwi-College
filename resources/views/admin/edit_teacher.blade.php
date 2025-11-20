<x-admin-layout title="Edit Pengajar">
  <div class="max-w-2xl mx-auto py-10">
      <h2 class="text-2xl font-bold mb-6">Edit Pengajar</h2>

      <form action="{{ route('admin.teachers.update', $teacher) }}" method="POST" class="bg-white shadow rounded-lg p-6">
          @csrf
          @method('PUT')

          <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Nama</label>
              <input type="text" name="name" required value="{{ old('name', $teacher->user->name ?? '') }}" class="mt-1 block w-full border rounded px-3 py-2">
          </div>

          <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Email</label>
              <input type="email" name="email" required value="{{ old('email', $teacher->user->email ?? '') }}" class="mt-1 block w-full border rounded px-3 py-2">
          </div>

          <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
              <input type="text" name="phone" value="{{ old('phone', $teacher->user->phone ?? '') }}" class="mt-1 block w-full border rounded px-3 py-2">
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
              <p class="text-xs text-gray-500 mt-1">Perubahan status akan mengaktifkan atau menonaktifkan akses akun guru.</p>
          </div>

          <div class="flex gap-2">
              <button class="px-4 py-2 bg-[#2E529F] text-white rounded">Simpan</button>
              <a href="{{ route('admin.teachers.index') }}" class="px-4 py-2 bg-gray-200 rounded">Batal</a>
          </div>
      </form>
  </div>
</x-admin-layout>
