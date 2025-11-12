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
                      <th class="px-4 py-3">Aksi</th>
                  </tr>
              </thead>
              <tbody>
                  @forelse($students as $s)
                  <tr class="border-t">
                      <td class="px-4 py-3">{{ $s->id }}</td>
                      <td class="px-4 py-3">{{ $s->user->name ?? 'N/A' }}</td>
                      <td class="px-4 py-3">{{ $s->user->email ?? '-' }}</td>
                      <td class="px-4 py-3">{{ $s->classRoom?->grade }} - {{ $s->classRoom?->name ?? '-' }}</td>
                      <td class="px-4 py-3">{{ $s->created_at?->format('d M Y') ?? '-' }}</td>
                      <td class="px-4 py-3">
                          <div class="flex items-center gap-2">
                              @if($s->user && $s->user->email)
                              <form action="{{ route('admin.students.clear_email', $s) }}" method="POST" onsubmit="return confirm('Yakin hapus email siswa ini?');">
                                  @csrf
                                  <button class="px-3 py-1 bg-yellow-500 text-white rounded">Hapus Email</button>
                              </form>
                              @endif

                              <form action="{{ route('admin.students.destroy', $s) }}" method="POST" onsubmit="return confirm('Yakin hapus akun siswa ini? Semua data akun akan dihapus.');">
                                  @csrf
                                  @method('DELETE')
                                  <button class="px-3 py-1 bg-red-600 text-white rounded">Hapus Akun</button>
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
