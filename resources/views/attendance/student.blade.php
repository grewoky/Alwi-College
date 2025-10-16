<x-app-layout>
<div class="p-6 max-w-xl mx-auto">
  <h1 class="text-2xl font-bold mb-4">Ringkasan Absensi</h1>
  <p class="mb-2">Total Hadir: <b>{{ $presentCount }}</b></p>
  <p>Hari ini: 
    @if($todayStatus === 'present') <span class="text-green-700">Hadir</span>
    @elseif($todayStatus === 'alpha') <span class="text-red-700">Alpha</span>
    @else <span class="text-gray-600">Tidak ada jadwal</span>
    @endif
  </p>
</div>
</x-app-layout>
