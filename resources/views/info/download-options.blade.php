{{-- resources/views/info/download-options.blade.php --}}
@php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

$u = Auth::user();
$isAdmin = false;
if ($u) {
    $isAdmin = DB::table('model_has_roles')
        ->join('roles','roles.id','=','model_has_roles.role_id')
        ->where('model_has_roles.model_type', get_class($u))
        ->where('model_has_roles.model_id', $u->id)
        ->where('roles.name','admin')
        ->exists();
}
@endphp

<x-admin-layout>
  <x-slot name="title">Download Opsi • Admin</x-slot>

  <div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- Page Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Opsi Download File</h1>
        <p class="text-gray-600">Pilih opsi download yang sesuai dengan kebutuhan Anda</p>
      </div>

      @unless($isAdmin)
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
          <p class="text-sm">⚠️ Anda tidak memiliki akses ke halaman ini. Hanya admin yang dapat menggunakan fitur download.</p>
        </div>
      @else

      <div class="grid md:grid-cols-2 gap-6 mb-8">
        
        <!-- Option 1: Download Semua -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900">Download Semua File</h3>
          </div>
          <p class="text-gray-600 text-sm mb-4">Unduh semua file dari semua siswa dalam satu file ZIP yang terorganisir berdasarkan nama siswa.</p>
          <a href="{{ route('info.downloadAll') }}" class="inline-block px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition">
            📦 Mulai Download
          </a>
        </div>

        <!-- Option 2: Download Berdasarkan Tipe -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900">Download Berdasarkan Tipe</h3>
          </div>
          <p class="text-gray-600 text-sm mb-4">Pilih tipe file untuk diunduh (PDF, Gambar, Dokumen, Spreadsheet, Presentasi, atau Arsip).</p>
          <div class="space-y-2 mb-4">
            @foreach(['Gambar', 'Dokumen', 'Spreadsheet', 'Presentasi', 'Arsip'] as $type)
              <form action="{{ route('info.download.by-type') }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">
                <button type="submit" class="block w-full text-left px-3 py-2 bg-gray-50 hover:bg-blue-50 text-gray-700 hover:text-blue-600 rounded text-sm font-medium transition">
                  {{ $type }}
                </button>
              </form>
            @endforeach
          </div>
        </div>

        <!-- Option 3: Download Terpilih -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition md:col-span-2">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900">Download File Terpilih</h3>
          </div>
          <p class="text-gray-600 text-sm mb-4">Pilih file tertentu untuk diunduh dalam satu file ZIP.</p>
          <a href="{{ route('info.admin.list') }}" class="inline-block px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition">
            ✓ Pilih File
          </a>
        </div>

        <!-- Option 4: Statistik File -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900">Statistik File</h3>
          </div>
          <p class="text-gray-600 text-sm mb-4">Lihat statistik lengkap tentang file yang ada: jumlah, tipe, dan ukuran total.</p>
          <button onclick="loadFileStats()" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg text-sm font-medium transition">
            📊 Lihat Statistik
          </button>
        </div>

      </div>

      <!-- File Stats Modal -->
      <div id="statsModal" style="display: none;" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-900">Statistik File</h2>
            <button onclick="closeStats()" class="text-gray-400 hover:text-gray-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
          <div id="statsContent" class="space-y-3">
            <div class="animate-spin inline-block w-5 h-5 border-2 border-blue-600 border-t-transparent rounded-full"></div>
            <p class="text-gray-600 text-sm">Memuat statistik...</p>
          </div>
        </div>
      </div>

      <!-- Info Download Table -->
      <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-bold text-gray-900">Jenis-Jenis File yang Didukung</h2>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
              <tr>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Kategori</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Tipe File</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Ekstensi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-3 text-sm text-gray-900">📄 Dokumen</td>
                <td class="px-6 py-3 text-sm text-gray-600">PDF, Word, Text</td>
                <td class="px-6 py-3 text-sm text-gray-600">.pdf, .doc, .docx, .txt</td>
              </tr>
              <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-3 text-sm text-gray-900">🖼️ Gambar</td>
                <td class="px-6 py-3 text-sm text-gray-600">JPG, PNG, GIF</td>
                <td class="px-6 py-3 text-sm text-gray-600">.jpg, .jpeg, .png, .gif</td>
              </tr>
              <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-3 text-sm text-gray-900">📊 Spreadsheet</td>
                <td class="px-6 py-3 text-sm text-gray-600">Excel</td>
                <td class="px-6 py-3 text-sm text-gray-600">.xls, .xlsx</td>
              </tr>
              <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-3 text-sm text-gray-900">🎨 Presentasi</td>
                <td class="px-6 py-3 text-sm text-gray-600">PowerPoint</td>
                <td class="px-6 py-3 text-sm text-gray-600">.ppt, .pptx</td>
              </tr>
              <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-3 text-sm text-gray-900">📦 Arsip</td>
                <td class="px-6 py-3 text-sm text-gray-600">ZIP, RAR, 7Z</td>
                <td class="px-6 py-3 text-sm text-gray-600">.zip, .rar, .7z</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      @endunless

    </div>
  </div>

  <!-- JavaScript -->
  <script>
    function loadFileStats() {
      const modal = document.getElementById('statsModal');
      const content = document.getElementById('statsContent');
      modal.style.display = 'flex';

      fetch('{{ route("info.stats") }}')
        .then(res => res.json())
        .then(data => {
          let html = `
            <div class="space-y-2">
              <div class="flex justify-between items-center p-3 bg-blue-50 rounded">
                <span class="text-gray-700 font-medium">Total File:</span>
                <span class="text-blue-600 font-bold text-lg">${data.total}</span>
              </div>
              <div class="flex justify-between items-center p-3 bg-green-50 rounded">
                <span class="text-gray-700 font-medium">Total Ukuran:</span>
                <span class="text-green-600 font-bold text-lg">${data.bySize} MB</span>
              </div>
              <div class="mt-4">
                <p class="text-sm font-medium text-gray-700 mb-2">Berdasarkan Tipe:</p>
                <div class="space-y-1">
          `;
          
          for (const [type, count] of Object.entries(data.byType)) {
            html += `<div class="flex justify-between text-sm text-gray-600">
              <span>${type}:</span>
              <span class="font-semibold">${count}</span>
            </div>`;
          }
          
          html += `
                </div>
              </div>
            </div>
          `;
          
          content.innerHTML = html;
        })
        .catch(err => {
          content.innerHTML = '<p class="text-red-600 text-sm">Gagal memuat statistik</p>';
        });
    }

    function closeStats() {
      document.getElementById('statsModal').style.display = 'none';
    }

    // Close modal when clicking outside
    document.getElementById('statsModal')?.addEventListener('click', function(e) {
      if (e.target === this) closeStats();
    });
  </script>
</x-admin-layout>
