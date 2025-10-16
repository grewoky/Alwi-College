<x-app-layout>
<div class="p-6 max-w-3xl mx-auto">
  <h1 class="text-2xl font-bold mb-4">Generate Jadwal Per 2 Hari</h1>
  @if(session('ok'))<div class="bg-green-100 text-green-800 p-2 mb-3 rounded">{{ session('ok') }}</div>@endif

  <form method="post" action="{{ route('lessons.generate') }}" class="space-y-3">
    @csrf
    <div>
      <label class="block text-sm font-medium">Kelas</label>
      <select name="class_room_id" class="border p-2 rounded w-full" required>
        @foreach($classes as $c)
          <option value="{{ $c->id }}">{{ $c->school->name }} - {{ $c->grade }} - {{ $c->name }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="block text-sm font-medium">Mata Pelajaran</label>
      <select name="subject_id" class="border p-2 rounded w-full">
        <option value="">(Kosong)</option>
        @foreach($subjects as $s)
          <option value="{{ $s->id }}">{{ $s->name }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="block text-sm font-medium">Guru</label>
      <select name="teacher_id" class="border p-2 rounded w-full" required>
        @foreach($teachers as $t)
          <option value="{{ $t->id }}">{{ $t->user->name ?? ('Teacher #'.$t->id) }}</option>
        @endforeach
      </select>
    </div>
    <div class="grid grid-cols-2 gap-3">
      <div>
        <label class="block text-sm font-medium">Mulai</label>
        <input type="date" name="start_date" class="border p-2 rounded w-full" required>
      </div>
      <div>
        <label class="block text-sm font-medium">Selesai</label>
        <input type="date" name="end_date" class="border p-2 rounded w-full" required>
      </div>
    </div>
    <button class="bg-blue-600 text-white px-4 py-2 rounded">Generate</button>
  </form>
</div>
</x-app-layout>
