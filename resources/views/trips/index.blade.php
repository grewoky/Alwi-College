<x-app-layout>
<div class="p-6 max-w-4xl mx-auto">
  <h1 class="text-2xl font-bold mb-4">Rekap Trip Guru</h1>
  <form method="get" class="flex gap-3 mb-4">
    <div>
      <label class="block text-sm font-medium">Dari</label>
      <input type="date" name="from" value="{{ $from }}" class="border p-2 rounded">
    </div>
    <div>
      <label class="block text-sm font-medium">Sampai</label>
      <input type="date" name="to" value="{{ $to }}" class="border p-2 rounded">
    </div>
    <button class="bg-gray-800 text-white px-3 py-2 rounded self-end">Terapkan</button>
  </form>

  <table class="min-w-full text-sm border">
    <thead class="bg-gray-100">
      <tr>
        <th class="p-2 border">Guru</th>
        <th class="p-2 border">Total Trip</th>
        <th class="p-2 border">Status 90 Trip</th>
      </tr>
    </thead>
    <tbody>
      @foreach($teachers as $t)
        <tr>
          <td class="p-2 border">{{ $t['name'] }}</td>
          <td class="p-2 border">{{ $t['total'] }}</td>
          <td class="p-2 border">
            @if($t['total'] >= 90)
              <span class="text-green-700 bg-green-100 px-2 py-1 rounded">Tercapai</span>
            @else
              <span class="text-yellow-700 bg-yellow-100 px-2 py-1 rounded">Belum ({{ 90 - $t['total'] }} lagi)</span>
            @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
</x-app-layout>
