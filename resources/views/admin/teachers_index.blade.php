<x-admin-layout title="Kelola Pengajar">
  <div class="max-w-6xl mx-auto py-10">
      <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold">Kelola Pengajar</h2>
          <a href="{{ route('admin.teachers.create') }}" class="px-4 py-2 bg-[#2E529F] text-white rounded">Tambah Pengajar</a>
      </div>

      <div class="bg-white shadow rounded-lg p-4">
          <table class="w-full text-left">
              <thead>
                  <tr>
                      <th class="py-2">#</th>
                      <th class="py-2">Nama</th>
                      <th class="py-2">Email</th>
                      <th class="py-2">Aksi</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach($teachers as $t)
                  <tr class="border-t">
                      <td class="py-2">{{ $t->id }}</td>
                      <td class="py-2">{{ $t->user->name ?? 'N/A' }}</td>
                      <td class="py-2">{{ $t->user->email ?? 'N/A' }}</td>
                      <td class="py-2">
                          <div class="flex gap-2">
                              <a href="{{ route('admin.teachers.edit', $t) }}" class="px-3 py-1 bg-blue-600 text-white rounded text-sm">Edit</a>
                              <form action="{{ route('admin.teachers.destroy', $t) }}" method="POST" onsubmit="return confirm('Hapus pengajar ini? Aksi tidak dapat dikembalikan.');">
                                  @csrf
                                  @method('DELETE')
                                  <button class="px-3 py-1 bg-red-600 text-white rounded text-sm">Hapus</button>
                              </form>
                          </div>
                      </td>
                  </tr>
                  @endforeach
              </tbody>
          </table>

          <div class="mt-4">
              {{ $teachers->links() }}
          </div>
      </div>
  </div>
</x-admin-layout>