<x-app-layout>
<div class="p-6 max-w-5xl mx-auto">
  <h1 class="text-2xl font-bold mb-4">Absensi — {{ $lesson->date }} | {{ $lesson->classRoom->school->name ?? '-' }} | Kelas {{ $lesson->classRoom->grade }}-{{ $lesson->classRoom->name }}</h1>

  @if(session('ok'))<div class="bg-green-100 text-green-800 p-2 mb-3 rounded">{{ session('ok') }}</div>@endif

  <form method="post" action="{{ route('attendance.store',$lesson->id) }}">
    @csrf
    <table class="min-w-full border text-sm">
      <thead class="bg-gray-100">
        <tr>
          <th class="p-2 border">#</th>
          <th class="p-2 border">Nama</th>
          <th class="p-2 border">Hadir?</th>
        </tr>
      </thead>
      <tbody>
        @foreach($students as $s)
          <tr>
            <td class="border p-2">{{ $loop->iteration }}</td>
            <td class="border p-2">{{ $s->user->name ?? '-' }}</td>
            <td class="border p-2">
              <input type="checkbox" name="status[{{ $s->id }}]" value="present"
                @checked(($existing[$s->id] ?? null) === 'present')>
              <span class="text-xs text-gray-600 ml-1">Centang = Hadir (tak dicentang = Alpha)</span>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">Simpan Absensi</button>
  </form>
</div>
</x-app-layout>
