<x-app-layout>
<div class="p-6 max-w-5xl mx-auto">
  <h1 class="text-2xl font-bold mb-4">Jadwal Mengajar</h1>

  <form method="get" class="flex gap-3 mb-4">
    <input type="date" name="date" value="{{ $filters['date'] ?? '' }}" class="border p-2 rounded">
    <input type="number" name="grade" placeholder="Tingkat (10/11/12)" value="{{ $filters['grade'] ?? '' }}" class="border p-2 rounded w-40">
    <input type="number" name="school_id" placeholder="School ID" value="{{ $filters['school_id'] ?? '' }}" class="border p-2 rounded w-40">
    <button class="bg-gray-800 text-white px-3 py-2 rounded">Filter</button>
  </form>

  <div class="overflow-x-auto rounded-xl shadow">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-100">
        <tr>
          <th class="p-2 text-left">Tanggal</th>
          <th class="p-2 text-left">Sekolah</th>
          <th class="p-2 text-left">Kelas</th>
          <th class="p-2 text-left">Mapel</th>
          <th class="p-2 text-left">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y">
        @foreach($lessons as $ls)
          <tr class="hover:bg-gray-50">
            <td class="p-2">{{ $ls->date }}</td>
            <td class="p-2">{{ $ls->classRoom->school->name ?? '-' }}</td>
            <td class="p-2">{{ $ls->classRoom->grade }} - {{ $ls->classRoom->name }}</td>
            <td class="p-2">{{ $ls->subject->name ?? '-' }}</td>
            <td class="p-2">
              <a href="{{ route('attendance.show',$ls->id) }}" class="text-blue-600 underline">Absen Kelas</a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $lessons->links() }}
  </div>
</div>
</x-app-layout>
