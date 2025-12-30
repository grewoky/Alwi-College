@php
    $cloudName = '';
    $uploadPreset = config('cloudinary.upload_preset');
    $cloudUrl = config('cloudinary.cloud_url');
    if (is_string($cloudUrl)) {
        $parts = parse_url($cloudUrl);
        $cloudName = $parts['host'] ?? '';
    }
@endphp

<x-app-layout>
<div class="p-6 max-w-3xl mx-auto">
  <h1 class="text-2xl font-bold mb-4">Upload Bukti Pembayaran</h1>

  @if(session('ok'))
    <div class="bg-green-100 text-green-800 p-3 mb-3 rounded">{{ session('ok') }}</div>
  @endif

  <form action="{{ route('pay.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3" id="paymentUploadForm">
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
      <p class="text-xs text-gray-500 mb-2">File dikirim langsung ke Cloudinary agar tidak terkena batas ukuran Vercel.</p>
      <div class="space-y-2">
        <button type="button" id="paymentUploadButton" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">Pilih / Ambil File</button>
        <div id="paymentUploadPreview" class="hidden border border-blue-200 rounded p-3 bg-blue-50 flex items-center gap-3">
          <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7l9-4 9 4-9 4-9-4zM21 7v6m0 4v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-2m18-4l-9 4-9-4" />
          </svg>
          <div class="flex-1">
            <div class="text-sm font-semibold text-gray-800" id="paymentUploadName"></div>
            <div class="text-xs text-gray-600" id="paymentUploadInfo"></div>
          </div>
          <button type="button" id="paymentReplaceButton" class="text-sm text-blue-700 hover:text-blue-900 font-medium">Ganti</button>
        </div>
      </div>

      <input type="hidden" name="cloudinary_public_id" id="paymentCloudinaryPublicId">
      <input type="hidden" name="cloudinary_secure_url" id="paymentCloudinarySecureUrl">
      <input type="hidden" name="cloudinary_format" id="paymentCloudinaryFormat">
      <input type="hidden" name="cloudinary_original_filename" id="paymentCloudinaryOriginal">
      <input type="file" name="proof" id="paymentFallbackFile" class="hidden">
      @error('proof')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
      @error('cloudinary_public_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Kirim Bukti</button>
  </form>

  <hr class="my-6">

  <h2 class="text-xl font-semibold mb-3">Riwayat Pembayaran</h2>
  <div class="overflow-x-auto -mx-4 sm:mx-0">
    <table class="min-w-[900px] w-full border border-gray-300 text-sm">
    <thead class="bg-gray-100">
      <tr>
        <th class="p-2 border">Tanggal</th>
        <th class="p-2 border">Periode</th>
        <th class="p-2 border">Nominal</th>
        <th class="p-2 border">Bukti</th>
        <th class="p-2 border">Status</th>
        <th class="p-2 border">Catatan</th>
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
        <td class="border p-2">{{ $p->note ? e($p->note) : '-' }}</td>
      </tr>
      @endforeach
    </tbody>
    </table>
  </div>
</div>

@once
  <script src="https://widget.cloudinary.com/v2.0/global/all.js"></script>
@endonce
<script>
  // Wait for Cloudinary widget to load before initializing
  function initPaymentCloudinaryWidget() {
    const cloudName = @json($cloudName);
    const uploadPreset = @json($uploadPreset);
    const uploadButton = document.getElementById('paymentUploadButton');
    const preview = document.getElementById('paymentUploadPreview');
    const nameEl = document.getElementById('paymentUploadName');
    const infoEl = document.getElementById('paymentUploadInfo');
    const replaceButton = document.getElementById('paymentReplaceButton');
    const publicIdInput = document.getElementById('paymentCloudinaryPublicId');
    const secureUrlInput = document.getElementById('paymentCloudinarySecureUrl');
    const formatInput = document.getElementById('paymentCloudinaryFormat');
    const originalInput = document.getElementById('paymentCloudinaryOriginal');
    const fallbackInput = document.getElementById('paymentFallbackFile');
    const form = document.getElementById('paymentUploadForm');

    // Check if all required elements exist
    if (!uploadButton || !cloudName || !uploadPreset) {
      console.error('Payment upload form elements missing.');
      return;
    }

    // Check if Cloudinary is available
    if (!window.cloudinary) {
      console.warn('Cloudinary widget not loaded. Retrying...');
      setTimeout(initPaymentCloudinaryWidget, 500);
      return;
    }

    console.log('Payment Cloudinary widget initialization started');

    const widget = window.cloudinary.createUploadWidget({
      cloudName: cloudName,
      uploadPreset: uploadPreset,
      multiple: false,
      resourceType: 'auto',
      maxFileSize: 10 * 1024 * 1024,
      clientAllowedFormats: ['jpg','jpeg','png','pdf'],
      sources: ['local','camera','google_drive','dropbox','url']
    }, function (error, result) {
      if (error) {
        console.error('Cloudinary upload error', error);
        alert('Upload gagal. Silakan coba lagi.');
        return;
      }

      if (result && result.event === 'success') {
        const info = result.info;
        publicIdInput.value = info.public_id || '';
        secureUrlInput.value = info.secure_url || '';
        formatInput.value = info.format || '';
        originalInput.value = info.original_filename || '';
        if (fallbackInput) fallbackInput.value = '';

        if (nameEl) {
          const extension = info.format ? '.' + info.format : '';
          nameEl.textContent = (info.original_filename || info.public_id) + extension;
        }
        if (infoEl) {
          const sizeKb = info.bytes ? Math.round(info.bytes / 1024) : null;
          infoEl.textContent = sizeKb ? sizeKb + ' KB' : '';
        }
        preview.classList.remove('hidden');
      }
    });

    const openWidget = function () {
      if (widget) {
        widget.open();
      }
    };

    uploadButton.addEventListener('click', openWidget);

    if (replaceButton) {
      replaceButton.addEventListener('click', function () {
        publicIdInput.value = '';
        secureUrlInput.value = '';
        formatInput.value = '';
        originalInput.value = '';
        if (nameEl) nameEl.textContent = '';
        if (infoEl) infoEl.textContent = '';
        preview.classList.add('hidden');
        openWidget();
      });
    }

    if (form) {
      form.addEventListener('submit', function (e) {
        const hasDirect = publicIdInput.value && secureUrlInput.value;
        const hasFallback = fallbackInput && fallbackInput.files && fallbackInput.files.length > 0;
        if (!hasDirect && !hasFallback) {
          e.preventDefault();
          alert('Silakan unggah bukti pembayaran terlebih dahulu.');
        }
      });
    }
  }

  // Initialize Cloudinary widget when page loads
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPaymentCloudinaryWidget);
  } else {
    initPaymentCloudinaryWidget();
  }
</script>
</x-app-layout>
