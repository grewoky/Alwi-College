<x-admin-layout title="Admin • Carousel Poster">
  <div id="carouselCloudinaryConfig"
       data-cloud-name="{{ config('cloudinary.cloud_name') ?? env('CLOUDINARY_CLOUD_NAME', '') }}"
       data-upload-preset="{{ config('cloudinary.upload_preset') ?? env('CLOUDINARY_UPLOAD_PRESET', '') }}"
       class="hidden"
  ></div>

  <div class="mb-8 bg-gradient-to-r from-indigo-600 via-indigo-700 to-purple-700 rounded-2xl p-8 text-white shadow-lg">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl sm:text-4xl font-bold mb-2"><span class="heading-inline">Carousel Poster</span></h1>
        <p class="text-indigo-100 text-base sm:text-lg">Upload poster, untuk carousel di halaman utama otomatis ter-update.</p>
      </div>
      <div class="text-6xl hidden md:block">🖼️</div>
    </div>
  </div>

  @if(session('ok'))
    <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded-lg shadow-md">
      {{ session('ok') }}
    </div>
  @endif

  @if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded-lg shadow-md">
      {{ session('error') }}
    </div>
  @endif

  <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm mb-8">
    <h2 class="text-xl font-bold text-slate-900 mb-2">Tambah Poster</h2>
    <p class="text-sm text-slate-500 mb-4">Gambar akan otomatis menyesuaikan ukuran carousel (di-crop rapi via Cloudinary + object-cover).</p>

    <form action="{{ route('admin.carousel-posters.store') }}" method="POST" id="carouselPosterForm" class="space-y-4">
      @csrf

      <div class="flex flex-col sm:flex-row sm:items-center gap-3">
        <button type="button" id="carouselUploadButton" class="px-5 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold transition">
          Upload Poster 
        </button>
        <button type="button" id="carouselClearButton" class="hidden px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 font-medium transition">
          Hapus Pilihan
        </button>
      </div>

      <div id="carouselPreview" class="hidden">
        <div class="mt-3 flex items-start gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200">
          <img id="carouselPreviewImg" src="" alt="Preview poster" class="w-28 h-20 rounded-lg object-cover border border-slate-200">
          <div class="flex-1">
            <div class="text-sm font-semibold text-slate-900" id="carouselPreviewName"></div>
            <div class="text-xs text-slate-500" id="carouselPreviewMeta"></div>
            <div class="text-xs text-slate-500 mt-1">Poster akan tampil pada carousel halaman utama setelah disimpan.</div>
          </div>
        </div>
      </div>

      <input type="hidden" name="cloudinary_public_id" id="carouselCloudinaryPublicId" value="{{ old('cloudinary_public_id') }}">
      <input type="hidden" name="cloudinary_secure_url" id="carouselCloudinarySecureUrl" value="{{ old('cloudinary_secure_url') }}">
      <input type="hidden" name="cloudinary_format" id="carouselCloudinaryFormat" value="{{ old('cloudinary_format') }}">
      <input type="hidden" name="cloudinary_original_filename" id="carouselCloudinaryOriginal" value="{{ old('cloudinary_original_filename') }}">

      @error('cloudinary_public_id')
        <div class="text-red-600 text-sm">{{ $message }}</div>
      @enderror
      @error('cloudinary_secure_url')
        <div class="text-red-600 text-sm">{{ $message }}</div>
      @enderror

      <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-lg bg-slate-900 hover:bg-slate-800 text-white font-semibold transition">
        Simpan Poster
      </button>
    </form>
  </div>

  <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
    <h2 class="text-xl font-bold text-slate-900 mb-4">Daftar Poster</h2>

    @if(empty($posters) || count($posters) === 0)
      <div class="p-10 rounded-xl border-2 border-dashed border-slate-200 text-center text-slate-600">
        Belum ada poster. Tambahkan poster pertama lewat tombol upload.
      </div>
    @else
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($posters as $idx => $p)
          <div class="border border-slate-200 rounded-xl overflow-hidden">
            <div class="aspect-[16/9] bg-slate-100">
              <img src="{{ $p->transformedUrl('f_auto,q_auto,c_fill,g_auto,w_1200,h_675') }}" alt="{{ $p->cloudinary_original_filename ?: 'Poster' }}" class="w-full h-full object-cover">
            </div>
            <div class="p-4">
              <div class="text-sm font-semibold text-slate-900">
                {{ $p->cloudinary_original_filename ?: $p->cloudinary_public_id }}
              </div>
              <div class="text-xs text-slate-500 mt-1">Posisi: {{ $p->position }} • Status: {{ $p->is_active ? 'Aktif' : 'Nonaktif' }}</div>

              <div class="mt-4 flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                  <form action="{{ route('admin.carousel-posters.move-up', $p) }}" method="POST">
                    @csrf
                    <button type="submit"
                      class="px-3 py-2 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50 font-medium transition disabled:opacity-40 disabled:cursor-not-allowed"
                      {{ $idx === 0 ? 'disabled' : '' }}
                      title="Naik">
                      ↑
                    </button>
                  </form>
                  <form action="{{ route('admin.carousel-posters.move-down', $p) }}" method="POST">
                    @csrf
                    <button type="submit"
                      class="px-3 py-2 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50 font-medium transition disabled:opacity-40 disabled:cursor-not-allowed"
                      {{ $idx === count($posters) - 1 ? 'disabled' : '' }}
                      title="Turun">
                      ↓
                    </button>
                  </form>
                </div>

                <form action="{{ route('admin.carousel-posters.destroy', $p) }}" method="POST" onsubmit="return confirm('Hapus poster ini?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="px-4 py-2 rounded-lg bg-red-50 hover:bg-red-100 text-red-700 font-medium transition">
                    Hapus
                  </button>
                </form>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>

  @once
    <script src="https://widget.cloudinary.com/v2.0/global/all.js" type="text/javascript"></script>
  @endonce

  <script type="text/javascript">
    (function(){
      const configEl = document.getElementById('carouselCloudinaryConfig');
      // Keep defaults consistent with the Info page to reduce env misconfig headaches.
      const cloudName = configEl?.dataset?.cloudName?.trim() || 'dln4ok2h5';
      const uploadPreset = configEl?.dataset?.uploadPreset?.trim() || 'JadwalMurid';

      const uploadButton = document.getElementById('carouselUploadButton');
      const clearButton = document.getElementById('carouselClearButton');
      const preview = document.getElementById('carouselPreview');
      const previewImg = document.getElementById('carouselPreviewImg');
      const previewName = document.getElementById('carouselPreviewName');
      const previewMeta = document.getElementById('carouselPreviewMeta');

      const publicIdInput = document.getElementById('carouselCloudinaryPublicId');
      const secureUrlInput = document.getElementById('carouselCloudinarySecureUrl');
      const formatInput = document.getElementById('carouselCloudinaryFormat');
      const originalInput = document.getElementById('carouselCloudinaryOriginal');
      const form = document.getElementById('carouselPosterForm');

      if (!uploadButton) return;

      let widget = null;

      const resetSelection = function () {
        if (publicIdInput) publicIdInput.value = '';
        if (secureUrlInput) secureUrlInput.value = '';
        if (formatInput) formatInput.value = '';
        if (originalInput) originalInput.value = '';

        if (previewImg) previewImg.src = '';
        if (previewName) previewName.textContent = '';
        if (previewMeta) previewMeta.textContent = '';
        preview?.classList.add('hidden');
        clearButton?.classList.add('hidden');
      };

      function ensureWidget(retryCount = 0) {
        if (widget) return true;

        if (!window.cloudinary) {
          if (retryCount < 20) {
            setTimeout(() => ensureWidget(retryCount + 1), 500);
          }
          return false;
        }

        if (!cloudName || !uploadPreset) {
          console.error('Cloudinary config missing', { cloudName, uploadPreset });
          return false;
        }

        widget = window.cloudinary.createUploadWidget({
          cloudName: cloudName,
          uploadPreset: uploadPreset,
          multiple: false,
          resourceType: 'image',
          clientAllowedFormats: ['jpg','jpeg','png','webp'],
          sources: ['local','camera','google_drive','dropbox','box','onedrive','url'],
          maxFileSize: 10 * 1024 * 1024
        }, function (error, result) {
          if (error) {
            console.error('Cloudinary upload error', error);
            alert('Upload gagal. Silakan coba lagi.');
            return;
          }

          if (result && result.event === 'success') {
            const info = result.info || {};
            if (publicIdInput) publicIdInput.value = info.public_id || '';
            if (secureUrlInput) secureUrlInput.value = info.secure_url || '';
            if (formatInput) formatInput.value = info.format || '';
            if (originalInput) originalInput.value = info.original_filename || '';

            if (previewImg && info.secure_url) {
              const transformed = String(info.secure_url).includes('/upload/')
                ? String(info.secure_url).replace('/upload/', '/upload/f_auto,q_auto,c_fill,g_auto,w_800,h_450/')
                : info.secure_url;
              previewImg.src = transformed;
            }

            const extension = info.format ? '.' + info.format : '';
            if (previewName) previewName.textContent = (info.original_filename || info.public_id || 'poster') + extension;
            if (previewMeta) {
              const sizeKb = info.bytes ? Math.round(info.bytes / 1024) : null;
              previewMeta.textContent = sizeKb ? (sizeKb + ' KB') : '';
            }

            preview?.classList.remove('hidden');
            clearButton?.classList.remove('hidden');
          }
        });

        return true;
      }

      // Kick off background init (so the widget is ready by the time user clicks)
      ensureWidget(0);

      uploadButton.addEventListener('click', function () {
        if (ensureWidget(0) && widget) {
          widget.open();
          return;
        }

        alert('Cloudinary belum siap / terblokir. Coba refresh, atau matikan AdBlock untuk domain ini.');
      });

      clearButton?.addEventListener('click', resetSelection);

      form?.addEventListener('submit', function (e) {
        const hasDirect = publicIdInput?.value && secureUrlInput?.value;
        if (!hasDirect) {
          e.preventDefault();
          alert('Silakan upload poster melalui Cloudinary terlebih dahulu.');
        }
      });
    })();
  </script>
</x-admin-layout>
