<x-app-layout>
  <div class="p-6 max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Upload Kisi-kisi / File Info</h1>

    @if(session('ok')) <div class="bg-green-100 text-green-800 p-3 mb-3 rounded">{{ session('ok') }}</div> @endif

    <form action="{{ route('info.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
      @csrf
      <div>
        <label class="block text-sm font-medium">Judul File</label>
        <input type="text" name="title" class="border w-full p-2 rounded" required>
        @error('title') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
      </div>
      <div>
        <label class="block text-sm font-medium">Pilih File</label>
        <input type="file" name="file" class="border w-full p-2 rounded" required>
        @error('file') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
      </div>
      <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Upload</button>
    </form>

    <hr class="my-6">

    <h2 class="text-xl font-semibold mb-3">Daftar File Anda</h2>
    @if($files->isEmpty())
      <p class="text-gray-600">Belum ada file diunggah.</p>
    @else
      <table class="min-w-full border border-gray-300 text-sm">
        <thead class="bg-gray-100">
          <tr>
            <th class="p-2 border">#</th>
            <th class="p-2 border">Judul</th>
            <th class="p-2 border">Tanggal</th>
            <th class="p-2 border">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($files as $f)
            <tr>
              <td class="border p-2">{{ $loop->iteration }}</td>
              <td class="border p-2">{{ $f->title }}</td>
              <td class="border p-2">{{ $f->created_at->format('d M Y H:i') }}</td>
              <td class="border p-2"><a href="{{ asset('storage/'.$f->file_path) }}" target="_blank" class="text-blue-600 underline">Lihat</a></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>
</x-app-layout>
