<x-app-layout>
  <div class="p-6 max-w-5xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Daftar File dari Pelajar</h1>

    @if($files->isEmpty())
      <p class="text-gray-600">Belum ada file yang diunggah oleh pelajar.</p>
    @else
      <table class="min-w-full border border-gray-300 text-sm">
        <thead class="bg-gray-100">
          <tr>
            <th class="p-2 border">#</th>
            <th class="p-2 border">Nama Pelajar</th>
            <th class="p-2 border">Judul</th>
            <th class="p-2 border">Tanggal Upload</th>
            <th class="p-2 border">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($files as $f)
            <tr>
              <td class="border p-2">{{ $loop->iteration }}</td>
              <td class="border p-2">{{ $f->student->user->name ?? '-' }}</td>
              <td class="border p-2">{{ $f->title }}</td>
              <td class="border p-2">{{ $f->created_at->format('d M Y H:i') }}</td>
              <td class="border p-2 text-center">
                <a href="{{ route('info.download', $f->id) }}" class="text-blue-600 underline">Download</a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>
</x-app-layout>
