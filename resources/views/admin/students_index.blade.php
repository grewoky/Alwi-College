<x-admin-layout title="Kelola Siswa">
  <div class="max-w-6xl mx-auto py-10">
      <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold"><span class="heading-inline">Kelola Siswa</span></h2>
          <div>
              <a href="{{ route('admin.students.create') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-600">Tambah Siswa</a>
          </div>
      </div>

      <form method="GET" action="{{ route('admin.students.index') }}" class="mb-6 flex gap-2">
          <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, email, atau NIS" class="px-3 py-2 border rounded w-full focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600 focus-visible:outline-none" />
          <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-600">Cari</button>
      </form>

      @if(session('success'))
          <div class="mb-4 text-green-700">{{ session('success') }}</div>
      @endif
      @if($errors->any())
          <div class="mb-4 text-red-700">{{ $errors->first() }}</div>
      @endif

      <div class="bg-white shadow rounded-lg overflow-hidden">
          <div class="overflow-x-auto">
          <table class="min-w-[960px] w-full table-auto">
              <thead>
                  <tr class="text-left bg-gray-50">
                      <th class="px-4 py-3">#</th>
                      <th class="px-4 py-3">Nama</th>
                      <th class="px-4 py-3">Email</th>
                      <th class="px-4 py-3">Kelas</th>
                     <th class="px-4 py-3">Nomor Telepon</th>
                      <th class="px-4 py-3">Terdaftar</th>
                      <th class="px-4 py-3">Status</th>
                     <th class="px-4 py-3">Aksi</th>
                  </tr>
              </thead>
              <tbody>
                  @forelse($students as $s)
                  <tr class="border-t">
                      <td class="px-4 py-3">{{ $students->firstItem() + $loop->index }}</td>
                      <td class="px-4 py-3">{{ $s->user->name ?? 'N/A' }}</td>
                      <td class="px-4 py-3">{{ $s->user->email ?? '-' }}</td>
                                            <td class="px-4 py-3">
                                                @if($s->classRoom)
                          {{ $s->classRoom->grade }} - {{ $s->classRoom->name }}
                                                    @if($s->classRoom->school)
                                                        <span class="text-xs text-gray-500">({{ $s->classRoom->school->name }})</span>
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                                                                        </td>
                                            <td class="px-4 py-3">{{ $s->user->phone ?? '-' }}</td>
                                            <td class="px-4 py-3">{{ $s->created_at?->format('d M Y') ?? '-' }}</td>
                                            <td class="px-4 py-3">
                                                @php($approved = (bool)($s->user->is_approved ?? false))
                                                @if($approved)
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 border border-green-200">Aktif</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700 border border-red-200">Nonaktif</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center justify-end">
                                                    <a href="{{ route('admin.students.edit', $s->id) }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600">Edit</a>
                                                </div>
                                            </td>
                  </tr>
                  @empty
                  <tr>
                      <td colspan="8" class="px-4 py-8 text-center text-gray-500">Belum ada siswa.</td>
                  </tr>
                  @endforelse
              </tbody>
          </table>
          </div>
      </div>

      <div class="mt-4">{{ $students->links() }}</div>
  </div>
</x-admin-layout>
