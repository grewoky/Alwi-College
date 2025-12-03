<x-admin-layout>
<div class="p-6 max-w-6xl mx-auto">
  <h1 class="text-2xl font-bold mb-4">Verifikasi Pembayaran</h1>

  @if(session('ok'))
    <div id="toast" class="mb-3 rounded border border-green-300 bg-green-50 text-green-800 px-4 py-2">
      {{ session('ok') }}
    </div>
    <script>setTimeout(()=>{const t=document.getElementById('toast'); if(t){t.style.display='none'}},2500);</script>
  @endif

  <form method="get" class="flex gap-3 items-end mb-4">
    <div>
      <label class="block text-sm font-medium">Status</label>
      <select name="status" class="border p-2 rounded">
        <option value="">Semua</option>
        <option value="pending"  @selected(request('status')=='pending')>Pending</option>
        <option value="approved" @selected(request('status')=='approved')>Approved</option>
        <option value="rejected" @selected(request('status')=='rejected')>Rejected</option>
      </select>
    </div>
    <div>
      <label class="block text-sm font-medium">Periode</label>
      <input type="month" name="month_period" value="{{ request('month_period') }}" class="border p-2 rounded">
    </div>
    <button class="bg-gray-800 text-white px-3 py-2 rounded">Filter</button>
  </form>

  <div class="overflow-x-auto rounded-xl shadow">
    <div class="overflow-x-auto -mx-4 sm:mx-0">
      <table class="min-w-[900px] w-full text-sm">
      <thead class="bg-gray-100">
        <tr>
          <th class="p-3 text-left">Siswa</th>
          <th class="p-3 text-left">Waktu</th>
          <th class="p-3 text-left">Periode</th>
          <th class="p-3 text-left">Nominal</th>
          <th class="p-3 text-left">Bukti</th>
          <th class="p-3 text-left">Status</th>
          <th class="p-3 text-left">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y">
        @foreach($payments as $p)
        <tr class="hover:bg-gray-50">
          <td class="p-3">{{ $p->student->user->name ?? '-' }}</td>
          <td class="p-3">{{ $p->created_at->format('d M Y H:i') }}</td>
          <td class="p-3">{{ $p->month_period ?? '-' }}</td>
          <td class="p-3">{{ $p->amount ? number_format($p->amount,0,',','.') : '-' }}</td>
          <td class="p-3"><a href="{{ route('pay.proof',$p->id) }}" target="_blank" class="text-blue-600 underline">Lihat</a></td>
          <td class="p-3">{{ ucfirst($p->status) }}</td>
          <td class="p-3">
            <a href="{{ route('pay.edit', $p->id) }}" class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 11l6 6L21 11l-6-6-6 6z" />
              </svg>
              Edit
            </a>
          </td>
        </tr>
        @endforeach
      </tbody>
      </table>
    </div>
  </div>

  <div class="mt-4">
    {{ $payments->links() }}
  </div>
</div>
</x-admin-layout>
