<x-admin-layout title="Kelola Siswa">
  <div class="max-w-6xl mx-auto py-10">
      <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold">Kelola Siswa</h2>
          <div>
              <a href="{{ route('admin.students.create') }}" class="px-4 py-2 bg-green-600 text-white rounded">Tambah Siswa</a>
          </div>
      </div>

      <form method="GET" action="{{ route('admin.students.index') }}" class="mb-6 flex gap-2">
          <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, email, atau NIS" class="px-3 py-2 border rounded w-full" />
          <button class="px-4 py-2 bg-indigo-600 text-white rounded">Cari</button>
      </form>

      @if(session('success'))
          <div class="mb-4 text-green-700">{{ session('success') }}</div>
      @endif
      @if($errors->any())
          <div class="mb-4 text-red-700">{{ $errors->first() }}</div>
      @endif

      <div class="bg-white shadow rounded-lg overflow-hidden">
          <table class="w-full table-auto">
              <thead>
                  <tr class="text-left bg-gray-50">
                      <th class="px-4 py-3">#</th>
                      <th class="px-4 py-3">Nama</th>
                      <th class="px-4 py-3">Email</th>
                      <th class="px-4 py-3">Kelas</th>
                      <th class="px-4 py-3">Terdaftar</th>
                      <th class="px-4 py-3">Status</th>
                  </tr>
              </thead>
              <tbody>
                  @forelse($students as $s)
                  <tr class="border-t">
                      <td class="px-4 py-3">{{ $students->firstItem() + $loop->index }}</td>
                      <td class="px-4 py-3">{{ $s->user->name ?? 'N/A' }}</td>
                      <td class="px-4 py-3">{{ $s->user->email ?? '-' }}</td>
                      <td class="px-4 py-3">{{ $s->classRoom?->grade }} - {{ $s->classRoom?->name ?? '-' }}</td>
                      <td class="px-4 py-3">{{ $s->created_at?->format('d M Y') ?? '-' }}</td>
                      <td class="px-4 py-3">
                          <div class="flex items-center gap-2">
                              <a href="{{ route('admin.students.edit', $s->id) }}" class="px-3 py-1 bg-blue-600 text-white rounded">Edit</a>

                              <form action="{{ route('admin.students.update', $s->id) }}" method="POST" onsubmit="return confirm('Ubah status aktif siswa?');">
                                  @csrf
                                  @method('PUT')
                                  <input type="hidden" name="name" value="{{ $s->user->name ?? '' }}">
                                  <input type="hidden" name="email" value="{{ $s->user->email ?? '' }}">
                                  <input type="hidden" name="class_room_id" value="{{ $s->class_room_id }}">
                                  <input type="hidden" name="nis" value="{{ $s->nis }}">
                                  <select name="is_approved" class="border p-1 rounded">
                                    <option value="1" @selected($s->user->is_approved ?? true)>Aktif</option>
                                    <option value="0" @selected(!($s->user->is_approved ?? true))>Nonaktif</option>
                                  </select>
                                  <button class="px-3 py-1 bg-gray-700 text-white rounded">Simpan</button>
                              </form>
                          </div>
                      </td>
                  </tr>
                  @empty
                  <tr>
                      <td colspan="6" class="px-4 py-8 text-center text-gray-500">Belum ada siswa.</td>
                  </tr>
                  @endforelse
              </tbody>
          </table>
      </div>

      <div class="mt-4">{{ $students->links() }}</div>
  </div>
</x-admin-layout>
