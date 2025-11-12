<x-admin-layout title="Pendaftar Menunggu Verifikasi">
  <div class="max-w-5xl mx-auto py-10">
      <h2 class="text-2xl font-bold mb-6">Pendaftar Menunggu Verifikasi</h2>

      @if(session('success'))
          <div class="mb-4 text-green-700">{{ session('success') }}</div>
      @endif

      <div class="bg-white shadow rounded-lg">
          <table class="w-full table-auto">
              <thead>
                  <tr class="text-left">
                      <th class="px-4 py-2">Nama</th>
                      <th class="px-4 py-2">Email</th>
                      <th class="px-4 py-2">Tanggal Daftar</th>
                      <th class="px-4 py-2">Aksi</th>
                  </tr>
              </thead>
              <tbody>
                  @forelse($users as $u)
                  <tr class="border-t">
                      <td class="px-4 py-3">{{ $u->name }}</td>
                      <td class="px-4 py-3">{{ $u->email }}</td>
                      <td class="px-4 py-3">{{ $u->created_at->format('d M Y H:i') }}</td>
                      <td class="px-4 py-3">
                          <form action="{{ route('admin.users.approve', $u) }}" method="POST" onsubmit="return confirm('Setujui akun ini?');">
                              @csrf
                              <button class="px-3 py-1 bg-green-600 text-white rounded">Setujui</button>
                          </form>
                      </td>
                  </tr>
                  @empty
                  <tr>
                      <td colspan="4" class="px-4 py-6 text-center text-gray-500">Tidak ada pendaftar baru.</td>
                  </tr>
                  @endforelse
              </tbody>
          </table>
      </div>
  </div>
</x-admin-layout>
