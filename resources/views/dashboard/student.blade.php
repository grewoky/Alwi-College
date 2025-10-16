<x-app-layout>
  <x-slot name="title">Siswa • Dashboard</x-slot>

  <div class="grid md:grid-cols-3 gap-4">
    <x-stat-card label="Total Hadir" :value="$presentCount" />
    <div class="bg-white border rounded-2xl p-4 shadow-sm md:col-span-2">
      <div class="text-xs text-gray-500 mb-1">Pembayaran Terakhir</div>
      @if($lastPayment)
        <div class="text-sm">Tanggal: {{ $lastPayment->created_at->format('d M Y H:i') }}</div>
        <div class="mt-1">
          @if($lastPayment->status==='approved')
            <x-badge color="green">Diterima</x-badge>
          @elseif($lastPayment->status==='rejected')
            <x-badge color="red">Ditolak</x-badge>
          @else
            <x-badge color="yellow">Menunggu</x-badge>
          @endif
        </div>
      @else
        <div class="text-gray-500 text-sm">Belum ada pembayaran.</div>
      @endif
      <div class="mt-3">
        <a href="{{ route('pay.index') }}" class="text-blue-600 underline text-sm">Upload Bukti</a>
      </div>
    </div>
  </div>

  <div class="mt-6 grid md:grid-cols-2 gap-4">
    <a href="{{ route('info.index') }}" class="bg-white border rounded-2xl p-4 shadow-sm hover:shadow-md transition">
      <div class="text-sm font-semibold mb-1">Upload Info / Kisi-kisi</div>
      <div class="text-gray-500 text-sm">Unggah berkas untuk pengajar.</div>
    </a>
  </div>
</x-app-layout>
