<x-admin-layout>
<div class="p-6 max-w-3xl mx-auto">
  <h1 class="text-2xl font-bold mb-4">Edit Pembayaran</h1>

  @if(session('ok'))
    <div class="mb-3 rounded border border-green-300 bg-green-50 text-green-800 px-4 py-2">
      {{ session('ok') }}
    </div>
  @endif
  @if(session('error'))
    <div class="mb-3 rounded border border-red-300 bg-red-50 text-red-800 px-4 py-2">
      {{ session('error') }}
    </div>
  @endif

  <div class="bg-white rounded-lg shadow p-6">
    <div class="mb-4">
      <p class="text-sm text-gray-600">Siswa</p>
      <p class="font-medium">{{ $payment->student->user->name ?? '-' }}</p>
    </div>
    <div class="mb-4">
      <p class="text-sm text-gray-600">Waktu Unggah</p>
      <p class="font-medium">{{ $payment->created_at->format('d M Y H:i') }}</p>
    </div>
    <div class="mb-4">
      <p class="text-sm text-gray-600">Bukti Pembayaran</p>
      <p><a href="{{ route('pay.proof', $payment->id) }}" target="_blank" class="text-blue-600 underline">Lihat File</a></p>
    </div>

    <form action="{{ route('pay.verify', $payment->id) }}" method="POST" class="space-y-4">
      @csrf
      <div>
        <label class="block text-sm font-medium">Status</label>
        <select name="status" class="border p-2 rounded w-full">
          <option value="approved" @selected($payment->status=='approved')>Approve</option>
          <option value="rejected" @selected($payment->status=='rejected') @if($payment->status=='approved') disabled @endif>Reject</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium">Catatan </label>
        <input type="text" name="note" value="{{ old('note', $payment->note) }}" class="border p-2 rounded w-full" />
      </div>

      <div class="flex gap-3">
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
        <a href="{{ route('pay.list') }}" class="px-4 py-2 border rounded">Batal</a>
      </div>
    </form>

    <div class="mt-4 flex justify-end">
      <form action="{{ route('pay.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="border border-red-300 text-red-700 px-3 py-1 rounded hover:bg-red-50">Hapus</button>
      </form>
    </div>
  </div>
</div>
</x-admin-layout>
