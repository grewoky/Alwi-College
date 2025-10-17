<x-admin-layout>
<div class="min-h-screen bg-gray-50 py-8">
  <div class="max-w-5xl mx-auto px-4">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-2">🚗 Rekap Trip Guru</h1>
      <p class="text-gray-600">Monitor dan kelola trip setiap guru (Target: 90 trip/bulan)</p>
    </div>

    <!-- Filter -->
    <form method="get" class="bg-white rounded-lg shadow p-6 mb-8">
      <div class="flex flex-wrap gap-4 items-end">
        <div>
          <label class="block text-sm font-semibold text-gray-900 mb-2">Dari Tanggal</label>
          <input type="date" name="from" value="{{ $from }}" class="border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent">
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-900 mb-2">Sampai Tanggal</label>
          <input type="date" name="to" value="{{ $to }}" class="border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent">
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition">
          🔍 Filter
        </button>
      </div>
    </form>

    <!-- Teachers Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="w-full">
        <thead class="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
          <tr>
            <th class="px-6 py-4 text-left font-semibold">Guru</th>
            <th class="px-6 py-4 text-center font-semibold">Total Trip</th>
            <th class="px-6 py-4 text-center font-semibold">Status (90)</th>
            <th class="px-6 py-4 text-center font-semibold">Progress</th>
            <th class="px-6 py-4 text-center font-semibold">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          @forelse($teachers as $t)
            <tr class="hover:bg-gray-50 transition">
              <td class="px-6 py-4 font-medium text-gray-900">{{ $t['name'] }}</td>
              <td class="px-6 py-4 text-center">
                <span class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full font-bold text-lg">
                  {{ $t['total'] }}
                </span>
              </td>
              <td class="px-6 py-4 text-center">
                @if($t['total'] >= 90)
                  <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full font-semibold">✓ Tercapai</span>
                @else
                  <span class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full font-semibold">
                    ⏳ {{ 90 - $t['total'] }} lagi
                  </span>
                @endif
              </td>
              <td class="px-6 py-4">
                <div class="w-full bg-gray-200 rounded-full h-2">
                  <div class="bg-blue-600 h-2 rounded-full transition" style="width: {{ min(100, ($t['total'] / 90) * 100) }}%"></div>
                </div>
                <div class="text-sm text-gray-600 mt-1 text-center">{{ round(($t['total'] / 90) * 100, 0) }}%</div>
              </td>
              <td class="px-6 py-4 text-center">
                <a href="{{ route('trips.show', $t['id']) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition inline-block">
                  📋 Detail & Edit
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                Tidak ada data guru
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
</x-admin-layout>
