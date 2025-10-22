<x-admin-layout>
<div class="min-h-screen bg-gray-50 py-8">
  <div class="max-w-5xl mx-auto px-4">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
      <div>
        <a href="{{ route('trips.index') }}" class="text-blue-600 hover:underline mb-2 inline-block">← Kembali</a>
        <h1 class="text-3xl font-bold text-gray-900">🚗 Trip Guru: {{ $teacher->user->name }}</h1>
        <p class="text-gray-600 mt-2">Total: <span class="font-bold text-lg">{{ $totalTrips }} / 90</span> trip</p>
      </div>
      <div class="text-right">
        <div class="text-3xl font-bold text-blue-600">{{ round(($totalTrips / 90) * 100, 0) }}%</div>
        <p class="text-gray-600">Progress</p>
      </div>
    </div>

    <!-- Progress Bar -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
      <div class="w-full bg-gray-200 rounded-full h-4">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 h-4 rounded-full transition" style="width: {{ min(100, ($totalTrips / 90) * 100) }}%"></div>
      </div>
      <div class="mt-2 flex justify-between text-sm text-gray-600">
        <span>{{ $totalTrips }} trip tercapai</span>
        <span>{{ 90 - $totalTrips }} lagi untuk target</span>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Add Manual Trip Form -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-lg p-6 sticky top-4">
          <h2 class="text-xl font-bold text-gray-900 mb-4">➕ Tambah Trip Manual</h2>
          <form method="POST" action="{{ route('trips.store', $teacher->id) }}" class="space-y-4">
            @csrf

            <!-- Date -->
            <div>
              <label class="block text-sm font-semibold text-gray-900 mb-2">Tanggal</label>
              <input type="date" name="date" required class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent">
              @error('date')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
            </div>

            <!-- Teaching Sessions -->
            <div>
              <label class="block text-sm font-semibold text-gray-900 mb-2">Jam Mengajar (0-3)</label>
              <input type="number" name="teaching_sessions" min="0" max="3" value="1" required class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent">
              @error('teaching_sessions')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
            </div>

            <!-- Sunday Bonus -->
            <div class="flex items-center gap-2">
              <input type="checkbox" name="sunday_bonus" id="sunday_bonus" class="w-4 h-4">
              <label for="sunday_bonus" class="text-sm font-medium text-gray-700">
                Bonus Hari Minggu (+3 poin)
              </label>
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 rounded-lg transition">
              ✓ Tambahkan
            </button>
          </form>

          @if(session('ok'))
            <div class="mt-4 bg-green-100 text-green-800 p-3 rounded-lg text-sm">
              ✓ {{ session('ok') }}
            </div>
          @endif
        </div>
      </div>

      <!-- Trip History -->
      <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow overflow-hidden">
          <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white px-6 py-4">
            <h2 class="text-xl font-bold">📅 Riwayat Trip</h2>
          </div>

          @if($trips->isEmpty())
            <div class="p-8 text-center text-gray-500">
              <p class="text-lg">Belum ada data trip untuk periode ini</p>
            </div>
          @else
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead class="bg-gray-100 border-b">
                  <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Tanggal</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900">Jam</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900">Bonus Minggu</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900">Total</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900">Aksi</th>
                  </tr>
                </thead>
                <tbody class="divide-y">
                  @foreach($trips as $trip)
                    @php
                      $tripValue = min(3, $trip->teaching_sessions + ($trip->sunday_bonus ? 3 : 0));
                      $dayName = \Carbon\Carbon::parse($trip->date)->format('l');
                    @endphp
                    <tr class="hover:bg-gray-50 transition" data-trip-id="{{ $trip->id }}">
                      <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($trip->date)->format('d M Y') }}</div>
                        <div class="text-sm text-gray-600">{{ $dayName }}</div>
                      </td>
                      <td class="px-6 py-4 text-center">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded font-semibold">{{ $trip->teaching_sessions }}</span>
                      </td>
                      <td class="px-6 py-4 text-center">
                        @if($trip->sunday_bonus)
                          <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded font-semibold">✓ Ya</span>
                        @else
                          <span class="text-gray-500">—</span>
                        @endif
                      </td>
                      <td class="px-6 py-4 text-center">
                        <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full font-bold">{{ $tripValue }}</span>
                      </td>
                      <td class="px-6 py-4 text-center">
                        <button onclick="editTrip({{ $trip->id }}, {{ $trip->teaching_sessions }}, {{ $trip->sunday_bonus ? 1 : 0 }})" class="text-blue-600 hover:underline text-sm font-medium">
                          Edit
                        </button>
                        <form method="POST" action="{{ route('trips.destroy', $trip->id) }}" style="display:inline;" onsubmit="return confirm('Hapus trip ini?');">
                          @csrf @method('DELETE')
                          <button type="submit" class="text-red-600 hover:underline text-sm font-medium ml-2">
                            Hapus
                          </button>
                        </form>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Edit Modal (Simple inline form) -->
<div id="editModal" style="display:none" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
  <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">✏️ Edit Trip</h2>
    <form method="POST" id="editForm" class="space-y-4">
      @csrf @method('PUT')

      <div>
        <label class="block text-sm font-semibold text-gray-900 mb-2">Jam Mengajar (0-3)</label>
        <input type="number" name="teaching_sessions" id="editSessions" min="0" max="3" required class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent">
      </div>

      <div class="flex items-center gap-2">
        <input type="checkbox" name="sunday_bonus" id="editBonus" class="w-4 h-4">
        <label for="editBonus" class="text-sm font-medium text-gray-700">Bonus Hari Minggu (+3 poin)</label>
      </div>

      <div class="flex gap-2">
        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg transition">
          💾 Simpan
        </button>
        <button type="button" onclick="closeEditModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-900 font-bold py-2 rounded-lg transition">
          Batal
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  // Get base route for trips update from Laravel
  const baseRoute = "{{ route('trips.update', ['trip' => 'TRIP_ID']) }}";
  
  function editTrip(tripId, sessions, bonus) {
    document.getElementById('editSessions').value = sessions;
    document.getElementById('editBonus').checked = bonus == 1;
    
    // Replace TRIP_ID with actual ID
    const actionUrl = baseRoute.replace('TRIP_ID', tripId);
    document.getElementById('editForm').action = actionUrl;
    
    document.getElementById('editModal').style.display = 'flex';
  }

  function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
  }

  document.addEventListener('click', function(e) {
    if (e.target.id === 'editModal') {
      closeEditModal();
    }
  });
</script>
</x-admin-layout>
