<x-app-layout>
  <x-slot name="title">Info • Unggah Kisi-kisi</x-slot>

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

            <form action="{{ route('info.store') }}" method="POST" enctype="multipart/form-data" id="form" class="space-y-6">
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
                <div class="flex gap-3">
                  <button 
                    type="button" 
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition flex items-center gap-2"
                    onclick="document.getElementById('fileInput').click()"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Pilih File
                  </button>
                </div>

                <!-- Hidden File Input -->
                <input 
                  type="file" 
                  id="fileInput" 
                  name="file"
                  class="hidden"
                  accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                  onchange="updateFileName(this)"
                  required
                >

                <!-- File Name Display -->
                <div id="fileNameDisplay" class="hidden mt-4">
                  <div class="flex items-center gap-3 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <svg class="w-6 h-6 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span id="fileName" class="text-sm font-medium text-gray-700"></span>
                    <button 
                      type="button" 
                      onclick="clearFile()" 
                      class="ml-auto text-red-600 hover:text-red-700 text-sm font-medium"
                    >
                      ✕ Hapus
                    </button>
                  </div>
                </div>

                @error('file')
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

  <!-- JavaScript for File Upload -->
  <script>
    function updateFileName(input) {
      const fileNameDisplay = document.getElementById('fileNameDisplay');
      const fileName = document.getElementById('fileName');
      const titleInput = document.getElementById('title');
      const materialInput = document.getElementById('material');

      if (input.files && input.files[0]) {
        const file = input.files[0];
        const displayName = file.name;
        const nameWithoutExt = file.name.replace(/\.[^/.]+$/, '');

        // Update display
        fileName.textContent = displayName;
        fileNameDisplay.classList.remove('hidden');

        // Auto-fill title with filename (without extension)
        titleInput.value = nameWithoutExt;

        // Auto-fill material if empty
        if (!materialInput.value) {
          materialInput.value = nameWithoutExt;
        }
      }
    }

    function clearFile() {
      const fileInput = document.getElementById('fileInput');
      const fileNameDisplay = document.getElementById('fileNameDisplay');
      const titleInput = document.getElementById('title');
      const materialInput = document.getElementById('material');

      fileInput.value = '';
      fileNameDisplay.classList.add('hidden');
      titleInput.value = '';
      materialInput.value = '';
    }

    // Prevent upload form submission if no file selected
    (function(){
      const uploadForm = document.getElementById('form');
      const fileInput = document.getElementById('fileInput');
      if (!uploadForm) return; // nothing to do

      uploadForm.addEventListener('submit', function(e) {
        if (!fileInput || !fileInput.files.length) {
          e.preventDefault();
          alert('Silakan pilih file terlebih dahulu');
        }
      });
    })();
  </script>
</x-app-layout>
