<x-admin-layout title="Kelola Pengajar">
  <div class="max-w-6xl mx-auto py-10">
      <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold">Kelola Pengajar</h2>
          <a href="{{ route('admin.teachers.create') }}" class="px-4 py-2 bg-[#2E529F] text-white rounded">Tambah Pengajar</a>
      </div>

      <div class="bg-white shadow rounded-lg p-4">
                    @if(session('success'))
                        <div class="mb-4 text-green-700">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="mb-4 text-red-700">{{ $errors->first() }}</div>
                    @endif

                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <table class="min-w-[900px] w-full text-left">
              <thead>
                  <tr>
                      <th class="py-2">#</th>
                      <th class="py-2">Nama</th>
                      <th class="py-2">Email</th>
                      <th class="py-2">Nomor Telepon</th>
                      <th class="py-2">Status</th>
                      <th class="py-2">Aksi</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach($teachers as $t)
                  <tr class="border-t">
                      <td class="py-2">{{ $teachers->firstItem() + $loop->index }}</td>
                                            <td class="py-2">{{ $t->user?->name ?? 'N/A' }}</td>
                                            <td class="py-2">{{ $t->user?->email ?? 'N/A' }}</td>
                                            <td class="py-2">{{ $t->user?->phone ?? '-' }}</td>
                      <td class="py-2">
                                                    @php($approved = (bool)($t->user?->is_approved ?? false))
                          @if($approved)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 border border-green-200">Aktif</span>
                          @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700 border border-red-200">Nonaktif</span>
                          @endif
                      </td>
                      <td class="py-2">
                          <div class="flex items-center justify-end">
                              <a href="{{ route('admin.teachers.edit', $t) }}" class="px-3 py-1 bg-blue-600 text-white rounded text-sm">Edit</a>
                          </div>
                      </td>
                  </tr>
                  @endforeach
              </tbody>
                        </table>
                    </div>

          <div class="mt-4">
              {{ $teachers->links() }}
          </div>
      </div>
  </div>
</x-admin-layout>