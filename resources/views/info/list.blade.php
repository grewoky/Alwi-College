{{-- resources/views/info/list.blade.php --}}
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

<x-app-layout>
  <x-slot name="title">Daftar Info File • Admin</x-slot>

  <div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- Page Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Daftar File dari Siswa</h1>
        <p class="text-gray-600">Kelola semua file kisi-kisi dan materi yang diunggah oleh siswa</p>
      </div>

      <!-- Success Message -->
      @if(session('ok'))
        <div id="toast" class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-start">
          <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
          </svg>
          <span>{{ session('ok') }}</span>
        </div>
        <script>
          setTimeout(()=>{ const t=document.getElementById('toast'); if(t){ t.style.display='none'; } }, 3000);
        </script>
      @endif

      @if($files->isEmpty())
        <!-- Empty State -->
        <div class="bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 p-12 text-center">
          <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          <p class="text-gray-600 text-lg font-medium">Belum ada file yang diunggah</p>
          <p class="text-gray-500 text-sm mt-1">Siswa akan mulai mengunggah file mereka ke sini</p>
        </div>
      @else
        <!-- Files Grid/List -->
        <div class="grid gap-6">
          @foreach($files as $f)
            <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition">
              <!-- Header Row -->
              <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-3">
                  <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M4 4a2 2 0 012-2h6a1 1 0 01.707.293l6 6a1 1 0 01.293.707v8a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"></path>
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $f->title }}</h3>
                    <p class="text-sm text-gray-600">{{ $f->created_at->format('d M Y H:i') }}</p>
                  </div>
                </div>
                <div class="flex gap-2">
                  <!-- Download Button -->
                  <a href="{{ route('info.download', $f->id) }}"
                     class="px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg text-sm font-medium transition"
                     title="Download File">
                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                  </a>

                  <!-- Delete Button (Admin only) -->
                  @if($isAdmin)
                    <form action="{{ route('info.destroy', $f->id) }}" method="POST" onsubmit="return confirm('Hapus file ini?');" class="inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit"
                        class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg text-sm font-medium transition"
                        title="Hapus File">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                      </button>
                    </form>
                  @endif
                </div>
              </div>

              <!-- Student Info -->
              <div class="mb-4 pb-4 border-b border-gray-200">
                <div class="text-sm text-gray-600">
                  <span class="font-semibold text-gray-900">{{ $f->student->user->name ?? '-' }}</span>
                  @if($f->student->classRoom)
                    <span class="text-gray-500"> • {{ $f->student->classRoom->name ?? 'Kelas tidak tersedia' }}</span>
                  @endif
                </div>
              </div>

              <!-- Details Grid -->
              <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @if($f->school)
                  <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Sekolah</p>
                    <p class="text-sm font-medium text-gray-900">{{ $f->school }}</p>
                  </div>
                @else
                  <div class="bg-gray-50 p-3 rounded-lg opacity-50">
                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Sekolah</p>
                    <p class="text-sm text-gray-400">-</p>
                  </div>
                @endif

                @if($f->class_name)
                  <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Kelas</p>
                    <p class="text-sm font-medium text-gray-900">{{ $f->class_name }}</p>
                  </div>
                @else
                  <div class="bg-gray-50 p-3 rounded-lg opacity-50">
                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Kelas</p>
                    <p class="text-sm text-gray-400">-</p>
                  </div>
                @endif

                @if($f->subject)
                  <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Pelajaran</p>
                    <p class="text-sm font-medium text-gray-900">{{ $f->subject }}</p>
                  </div>
                @else
                  <div class="bg-gray-50 p-3 rounded-lg opacity-50">
                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Pelajaran</p>
                    <p class="text-sm text-gray-400">-</p>
                  </div>
                @endif

                @if($f->material)
                  <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Materi</p>
                    <p class="text-sm font-medium text-gray-900">{{ $f->material }}</p>
                  </div>
                @else
                  <div class="bg-gray-50 p-3 rounded-lg opacity-50">
                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Materi</p>
                    <p class="text-sm text-gray-400">-</p>
                  </div>
                @endif
              </div>
            </div>
          @endforeach
        </div>

        <!-- Pagination atau Total -->
        <div class="mt-8 text-center text-gray-600 text-sm">
          Total: <span class="font-semibold">{{ $files->count() }}</span> file
        </div>
      @endif

    </div>
  </div>
</x-app-layout>
