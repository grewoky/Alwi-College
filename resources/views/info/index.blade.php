<x-app-layout>
  <x-slot name="title">Info • Unggah Kisi-kisi</x-slot>

  @php
      $infoCloudName = config('cloudinary.cloud_name') ?? env('CLOUDINARY_CLOUD_NAME');
      $infoUploadPreset = config('cloudinary.upload_preset') ?? env('CLOUDINARY_UPLOAD_PRESET');
      // Debug: Log if values are empty
      if (!$infoCloudName || !$infoUploadPreset) {
          \Log::warning('Cloudinary config missing (info page)', [
              'cloudName' => $infoCloudName,
              'uploadPreset' => $infoUploadPreset,
              'env_name' => env('CLOUDINARY_CLOUD_NAME'),
              'env_preset' => env('CLOUDINARY_UPLOAD_PRESET'),
          ]);
      }
  @endphp

  <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-gray-50">

    <!-- Hero Header Section -->
    <div class="mb-8 bg-gradient-to-r from-blue-600 via-cyan-600 to-teal-600 rounded-2xl p-8 text-white shadow-lg">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-4xl font-bold mb-2">Upload Kisi-kisi & Materi</h1>
          <p class="text-blue-100 text-lg">Bagikan materi pembelajaran dengan guru dan admin sekolah</p>
        </div>
        <div class="text-6xl hidden md:block">📚</div>
      </div>
    </div>

    <!-- Page Content -->
    <div class="py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Success/Error Messages -->
        @if(session('ok'))
          <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded-lg shadow-md flex items-start">
            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span>{{ session('ok') }}</span>
          </div>
        @endif

        @if(session('error'))
          <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded-lg shadow-md flex items-start">
            <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-5h2v2h-2v-2zm0-6h2v5h-2V7z" clip-rule="evenodd"></path>
            </svg>
            <span>{{ session('error') }}</span>
          </div>
        @endif

        <!-- Form Card Container -->
        <div class="grid grid-cols-1 gap-6 mb-12">
          <!-- Upload Form Card -->
          <div class="bg-white rounded-xl shadow-lg border-2 border-blue-100 p-8 hover:shadow-xl transition-all">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
              <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
              </svg>
              <span>Pengiriman Informasi</span>
            </h2>

            <form action="{{ route('info.store') }}" method="POST" enctype="multipart/form-data" id="infoUploadForm" class="space-y-6">
              @csrf

              <!-- Form Fields in Grid -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Sekolah -->
                <div>
                  <label for="school" class="block text-sm font-semibold text-gray-700 mb-2">
                    📍 Sekolah
                  </label>
                  <input 
                    type="text" 
                    id="school" 
                    name="school" 
                    placeholder="Nama Sekolah"
                    class="w-full px-4 py-3 border-2 border-blue-300 rounded-lg focus:outline-none focus:border-blue-600 transition"
                    value="{{ old('school') }}"
                  >
                  @error('school')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                  @enderror
                </div>

                <!-- Kelas -->
                <div>
                  <label for="class_name" class="block text-sm font-semibold text-gray-700 mb-2">
                    👥 Kelas
                  </label>
                  <input 
                    type="text" 
                    id="class_name" 
                    name="class_name" 
                    placeholder="Contoh: 10, XI A, 3 IPA"
                    class="w-full px-4 py-3 border-2 border-blue-300 rounded-lg focus:outline-none focus:border-blue-600 transition"
                    value="{{ old('class_name') }}"
                  >
                  @error('class_name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                  @enderror
                </div>

                <!-- Pelajaran -->
                <div>
                  <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2">
                    📚 Pelajaran
                  </label>
                  <input 
                    type="text" 
                    id="subject" 
                    name="subject" 
                    placeholder="Contoh: Matematika, Fisika"
                    class="w-full px-4 py-3 border-2 border-blue-300 rounded-lg focus:outline-none focus:border-blue-600 transition"
                    value="{{ old('subject') }}"
                  >
                  @error('subject')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                  @enderror
                </div>

                <!-- Materi -->
                <div>
                  <label for="material" class="block text-sm font-semibold text-gray-700 mb-2">
                    📝 Materi
                  </label>
                  <input 
                    type="text" 
                    id="material" 
                    name="material" 
                    placeholder="Nama Materi"
                    class="w-full px-4 py-3 border-2 border-blue-300 rounded-lg focus:outline-none focus:border-blue-600 transition"
                    value="{{ old('material') }}"
                  >
                  @error('material')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                  @enderror
                </div>
              </div>

              <!-- File Upload Section -->
              <div class="pt-6 border-t border-gray-200">
                <label class="block text-sm font-semibold text-gray-700 mb-4">
                  📎 Pilih File
                </label>
                <p class="text-sm text-gray-500 mb-3">
                  File diunggah langsung ke Cloudinary (maks. 10 MB). Format yang didukung: pdf, doc, docx, xls, xlsx, ppt, pptx, jpg, jpeg, png, gif, txt, zip, rar, 7z.
                </p>
                <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                  <button 
                    type="button" 
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition flex items-center gap-2"
                    id="infoUploadButton"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Upload via Cloudinary
                  </button>
                  <button 
                    type="button"
                    id="infoReplaceButton"
                    class="hidden px-4 py-2 border border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50 font-medium transition"
                  >
                    Ganti File
                  </button>
                </div>

                <div id="fileNameDisplay" class="hidden mt-4">
                  <div class="flex items-center gap-3 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <svg class="w-6 h-6 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <div class="flex-1">
                      <span id="infoFileName" class="text-sm font-medium text-gray-700"></span>
                      <div id="infoFileMeta" class="text-xs text-gray-500"></div>
                    </div>
                    <button 
                      type="button" 
                      id="infoClearButton"
                      class="ml-auto text-red-600 hover:text-red-700 text-sm font-medium"
                    >
                      ✕ Hapus
                    </button>
                  </div>
                </div>

                <input type="hidden" name="cloudinary_public_id" id="infoCloudinaryPublicId">
                <input type="hidden" name="cloudinary_secure_url" id="infoCloudinarySecureUrl">
                <input type="hidden" name="cloudinary_format" id="infoCloudinaryFormat">
                <input type="hidden" name="cloudinary_original_filename" id="infoCloudinaryOriginal">
                <input type="hidden" name="cloudinary_resource_type" id="infoCloudinaryResourceType">
                <input type="file" name="file" id="infoFallbackFile" class="hidden">

                @error('file')
                  <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
                @error('cloudinary_public_id')
                  <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
              </div>

              <!-- Hidden Title Field -->
              <input 
                type="hidden" 
                id="title" 
                name="title"
              >

              <!-- Submit Button -->
              <div class="pt-6">
                <button 
                  type="submit" 
                  class="w-full px-6 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-lg rounded-lg transition shadow-md hover:shadow-lg"
                >
                  ✓ Kirim
                </button>
              </div>

            </form>
          </div>
        </div>

        <!-- Files List Section -->
        <div class="mt-12">
          <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
            <svg class="w-7 h-7 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
              <path d="M3 1a1 1 0 011-1h12a1 1 0 011 1H3zm0 4h14v2H3V5zm0 4h14v2H3V9zm0 4h14v2H3v-2z"></path>
            </svg>
            File Anda
          </h2>

          @if($files->isEmpty())
            <div class="bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 p-12 text-center">
              <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
              <p class="text-gray-600 text-lg font-medium">Belum ada file yang diunggah</p>
              <p class="text-gray-500 text-sm mt-1">Mulai dengan mengisi form di atas untuk mengunggah file pertama Anda</p>
            </div>
          @else
            <!-- Responsive list: horizontal scroll on mobile, grid on desktop -->
            <div class="overflow-x-auto md:overflow-visible -mx-4 md:mx-0">
              <div class="flex md:grid md:grid-cols-1 lg:grid-cols-1 xl:grid-cols-2 2xl:grid-cols-3 gap-8 px-4 md:px-0 flex-nowrap md:flex-none">
              @foreach($files as $f)
                <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition min-w-[280px] sm:min-w-[320px] md:min-w-0">
                  <div class="flex items-start justify-between">
                    <div class="flex-1">
                      <!-- Header -->
                      <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                          <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 012-2h6a1 1 0 01.707.293l6 6a1 1 0 01.293.707v8a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"></path>
                          </svg>
                        </div>
                        <div>
                          <h3 class="font-semibold text-gray-900 break-words">{{ $f->title }}</h3>
                          <p class="text-sm text-gray-500">{{ $f->created_at->format('d M Y H:i') }}</p>
                        </div>
                      </div>

                      <!-- Info Grid -->
                      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4 pt-4 border-t border-gray-100">
                        @if($f->school)
                          <div>
                            <p class="text-xs font-medium text-gray-500 uppercase">Sekolah</p>
                            <p class="text-sm font-semibold text-gray-900 whitespace-normal break-words">{{ $f->school }}</p>
                          </div>
                        @endif
                        @if($f->class_name)
                          <div>
                            <p class="text-xs font-medium text-gray-500 uppercase">Kelas</p>
                            <p class="text-sm font-semibold text-gray-900 whitespace-normal break-words">{{ $f->class_name }}</p>
                          </div>
                        @endif
                        @if($f->subject)
                          <div>
                            <p class="text-xs font-medium text-gray-500 uppercase">Pelajaran</p>
                            <p class="text-sm font-semibold text-gray-900 whitespace-normal break-words">{{ $f->subject }}</p>
                          </div>
                        @endif
                        @if($f->material)
                          <div>
                            <p class="text-xs font-medium text-gray-500 uppercase">Materi</p>
                            <p class="text-sm font-semibold text-gray-900 whitespace-normal break-words">{{ $f->material }}</p>
                          </div>
                        @endif
                      </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2 ml-4 flex-shrink-0">
                      <a 
                        href="{{ route('info.file', $f->id) }}" 
                        target="_blank" 
                        class="px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg text-sm font-medium transition"
                        title="Download/View File"
                      >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                      </a>
                      <form action="{{ route('info.destroy', $f->id) }}" method="POST" onsubmit="return confirm('Hapus file ini?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button 
                          type="submit" 
                          class="px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg text-sm font-medium transition"
                          title="Hapus File"
                        >
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                          </svg>
                        </button>
                      </form>
                    </div>
                  </div>
                </div>
              @endforeach
              </div>
            </div>
          @endif
        </div>

      </div>
    </div>
  </div>
  @once
    <script src="https://widget.cloudinary.com/v2.0/global/all.js" type="text/javascript"></script>
  @endonce
  <script type="text/javascript">
    window.cloudinaryInfoInitialized = false;
    
    // Wait for Cloudinary script to load before initializing
    function initCloudinaryWidget(retryCount = 0) {
      const cloudName = @json($infoCloudName);
      const uploadPreset = @json($infoUploadPreset);
      
      console.log('initCloudinaryWidget called (attempt ' + (retryCount + 1) + ')', { cloudName, uploadPreset, cloudinaryReady: !!window.cloudinary });
      
      const uploadButton = document.getElementById('infoUploadButton');
      const replaceButton = document.getElementById('infoReplaceButton');
      const clearButton = document.getElementById('infoClearButton');
      const preview = document.getElementById('fileNameDisplay');
      const fileNameEl = document.getElementById('infoFileName');
      const fileMetaEl = document.getElementById('infoFileMeta');
      const titleInput = document.getElementById('title');
      const materialInput = document.getElementById('material');
      const publicIdInput = document.getElementById('infoCloudinaryPublicId');
      const secureUrlInput = document.getElementById('infoCloudinarySecureUrl');
      const formatInput = document.getElementById('infoCloudinaryFormat');
      const originalInput = document.getElementById('infoCloudinaryOriginal');
      const resourceInput = document.getElementById('infoCloudinaryResourceType');
      const fallbackInput = document.getElementById('infoFallbackFile');
      const form = document.getElementById('infoUploadForm');

      // Check if all required elements exist
      if (!uploadButton) {
        console.error('Info upload button not found. Check if id="infoUploadButton" exists in HTML.');
        return;
      }
      if (!cloudName) {
        console.error('CLOUDINARY_CLOUD_NAME is empty. Check .env file or config/cloudinary.php', {
          cloudName,
          uploadPreset
        });
        return;
      }
      if (!uploadPreset) {
        console.error('CLOUDINARY_UPLOAD_PRESET is empty. Check .env file or config/cloudinary.php', {
          cloudName,
          uploadPreset
        });
        return;
      }

      // Check if Cloudinary is available - with max retry attempts
      if (!window.cloudinary) {
        if (retryCount < 20) {
          console.warn('Cloudinary widget not loaded (attempt ' + (retryCount + 1) + '/20). Retrying in 500ms...');
          setTimeout(() => initCloudinaryWidget(retryCount + 1), 500);
        } else {
          console.error('Cloudinary widget failed to load after 20 attempts');
        }
        return;
      }
      
      // Mark as initialized
      window.cloudinaryInfoInitialized = true;
      console.log('Cloudinary widget initialization started');

      const resetSelection = function () {
        publicIdInput.value = '';
        secureUrlInput.value = '';
        formatInput.value = '';
        originalInput.value = '';
        if (resourceInput) resourceInput.value = '';
        if (fileNameEl) fileNameEl.textContent = '';
        if (fileMetaEl) fileMetaEl.textContent = '';
        if (fallbackInput) fallbackInput.value = '';
        preview.classList.add('hidden');
        replaceButton?.classList.add('hidden');
      };

      const widget = window.cloudinary.createUploadWidget({
        cloudName: cloudName,
        uploadPreset: uploadPreset,
        multiple: false,
        resourceType: 'auto',
        maxFileSize: 10 * 1024 * 1024,
        clientAllowedFormats: ['pdf','doc','docx','xls','xlsx','ppt','pptx','jpg','jpeg','png','gif','txt','zip','rar','7z'],
        sources: ['local','camera','google_drive','dropbox','box','onedrive','url']
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
          if (resourceInput) resourceInput.value = info.resource_type || '';

          const extension = info.format ? '.' + info.format : '';
          const displayName = (info.original_filename || info.public_id) + extension;
          if (fileNameEl) fileNameEl.textContent = displayName;
          if (fileMetaEl) {
            const sizeKb = info.bytes ? Math.round(info.bytes / 1024) : null;
            fileMetaEl.textContent = sizeKb ? sizeKb + ' KB • ' + (info.resource_type || 'file') : (info.resource_type || '');
          }

          // Autofill title/material if kosong
          if (titleInput && !titleInput.value) {
            titleInput.value = info.original_filename || info.public_id || '';
          }
          if (materialInput && !materialInput.value) {
            materialInput.value = info.original_filename || info.public_id || '';
          }

          preview.classList.remove('hidden');
          replaceButton?.classList.remove('hidden');
        }
      });

      const openWidget = function () {
        widget.open();
      };

      uploadButton.addEventListener('click', openWidget);

      if (replaceButton) {
        replaceButton.addEventListener('click', function () {
          resetSelection();
          openWidget();
        });
      }

      if (clearButton) {
        clearButton.addEventListener('click', resetSelection);
      }

      if (form) {
        form.addEventListener('submit', function (e) {
          const hasDirect = publicIdInput.value && secureUrlInput.value;
          const hasFallback = fallbackInput && fallbackInput.files && fallbackInput.files.length > 0;
          if (!hasDirect && !hasFallback) {
            e.preventDefault();
            alert('Silakan unggah file melalui Cloudinary terlebih dahulu.');
          }
        });
      }
    }

    // Initialize Cloudinary widget when page loads
    function startCloudinaryInit() {
      console.log('Starting info Cloudinary initialization...');
      initCloudinaryWidget(0);
    }
    
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', startCloudinaryInit);
    } else {
      startCloudinaryInit();
    }
    
    // Also wait a bit more to ensure script is loaded
    setTimeout(startCloudinaryInit, 1000);
  </script>
</x-app-layout>
