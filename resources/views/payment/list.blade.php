<x-app-layout>
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
    <table class="min-w-full text-sm">
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
          <td class="p-3"><a href="{{ asset('storage/'.$p->proof_path) }}" target="_blank" class="text-blue-600 underline">Lihat</a></td>
          <td class="p-3">{{ ucfirst($p->status) }}</td>
          <td class="p-3">
            <form action="{{ route('pay.verify',$p->id) }}" method="POST" class="flex flex-wrap gap-2 items-center">
              @csrf
              <select name="status" class="border rounded p-1">
                <option value="approved">Approve</option>
                <option value="rejected">Reject</option>
              </select>
              <input type="text" name="note" placeholder="Catatan (opsional)" class="border p-1 rounded">
              <button class="bg-blue-600 text-white px-2 py-1 rounded">Simpan</button>
            </form>

            <form action="{{ route('pay.destroy',$p->id) }}" method="POST"
                  onsubmit="return confirm('Yakin hapus data ini?')"
                  class="inline-block mt-2">
              @csrf
              @method('DELETE')
              <button class="border border-red-300 text-red-700 px-2 py-1 rounded hover:bg-red-50 inline-flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round"
                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.917 21H8.083A2.25 2.25 0 0 1 5.84 19.673L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0V4.5A1.5 1.5 0 0 0 13.5 3h-3A1.5 1.5 0 0 0 9 4.5v1.043m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
                Hapus
              </button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $payments->links() }}
  </div>
</div>
</x-app-layout>
