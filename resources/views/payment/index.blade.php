<x-app-layout>
<div class="p-6 max-w-3xl mx-auto">
  <h1 class="text-2xl font-bold mb-4">Upload Bukti Pembayaran</h1>

  @if(session('ok'))
    <div class="bg-green-100 text-green-800 p-3 mb-3 rounded">{{ session('ok') }}</div>
  @endif

  <form action="{{ route('pay.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
    @csrf
    <div>
      <label class="block text-sm font-medium">Periode (YYYY-MM)</label>
      <input type="month" name="month_period" class="border w-full p-2 rounded">
      @error('month_period')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <div>
      <label class="block text-sm font-medium">Nominal (opsional)</label>
      <input type="number" name="amount" class="border w-full p-2 rounded" min="0">
      @error('amount')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <div>
      <label class="block text-sm font-medium">Bukti (jpg/png/pdf)</label>
      <input type="file" name="proof" class="border w-full p-2 rounded" required>
      @error('proof')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Kirim Bukti</button>
  </form>

  <hr class="my-6">

  <h2 class="text-xl font-semibold mb-3">Riwayat Pembayaran</h2>
  <table class="min-w-full border border-gray-300 text-sm">
    <thead class="bg-gray-100">
      <tr>
        <th class="p-2 border">Tanggal</th>
        <th class="p-2 border">Periode</th>
        <th class="p-2 border">Nominal</th>
        <th class="p-2 border">Bukti</th>
        <th class="p-2 border">Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach($payments as $p)
      <tr>
        <td class="border p-2">{{ $p->created_at->format('d M Y H:i') }}</td>
        <td class="border p-2">{{ $p->month_period ?? '-' }}</td>
        <td class="border p-2">{{ $p->amount ? number_format($p->amount,0,',','.') : '-' }}</td>
  <td class="border p-2"><a href="{{ route('pay.proof',$p->id) }}" target="_blank" class="text-blue-600 underline">Lihat</a></td>
        <td class="border p-2">
          @if($p->status=='pending') <span class="text-yellow-700 bg-yellow-100 px-2 py-1 rounded">Menunggu</span>
          @elseif($p->status=='approved') <span class="text-green-700 bg-green-100 px-2 py-1 rounded">Diterima</span>
          @else <span class="text-red-700 bg-red-100 px-2 py-1 rounded">Ditolak</span>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
</x-app-layout>
