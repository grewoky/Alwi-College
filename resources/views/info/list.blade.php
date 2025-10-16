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
  <div class="p-6 max-w-5xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Daftar File dari Pelajar</h1>

    @if(session('ok'))
      <div id="toast" class="mb-3 rounded border border-green-300 bg-green-50 text-green-800 px-4 py-2">
        {{ session('ok') }}
      </div>
      <script>
        setTimeout(()=>{ const t=document.getElementById('toast'); if(t){ t.style.display='none'; } }, 2500);
      </script>
    @endif

    @if($files->isEmpty())
      <p class="text-gray-600">Belum ada file yang diunggah oleh pelajar.</p>
    @else
      <div class="overflow-x-auto rounded-xl shadow">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-100 text-gray-700">
            <tr>
              <th class="p-3 text-left">#</th>
              <th class="p-3 text-left">Nama Pelajar</th>
              <th class="p-3 text-left">Judul</th>
              <th class="p-3 text-left">Tanggal Upload</th>
              <th class="p-3 text-left">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            @foreach($files as $f)
              <tr class="hover:bg-gray-50">
                <td class="p-3">{{ $loop->iteration }}</td>
                <td class="p-3">{{ $f->student->user->name ?? '-' }}</td>
                <td class="p-3">{{ $f->title }}</td>
                <td class="p-3">{{ $f->created_at->format('d M Y H:i') }}</td>
                <td class="p-3 flex gap-2">
                  {{-- tombol download --}}
                  <a href="{{ route('info.download', $f->id) }}"
                     class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                      <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M7.5 12 12 16.5m0 0L16.5 12M12 16.5V3" />
                    </svg>
                    <span>Download</span>
                  </a>

                  {{-- tombol hapus hanya untuk admin --}}
                  @if($isAdmin)
                    <form action="{{ route('info.destroy', $f->id) }}" method="POST"
                          onsubmit="return confirm('Yakin hapus file ini? Tindakan tidak bisa dibatalkan.');">
                      @csrf
                      @method('DELETE')
                      <button
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-red-300 text-red-700 hover:bg-red-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                          <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.917 21H8.083A2.25 2.25 0 0 1 5.84 19.673L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0V4.5A1.5 1.5 0 0 0 13.5 3h-3A1.5 1.5 0 0 0 9 4.5v1.043m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                        <span>Hapus</span>
                      </button>
                    </form>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
</x-app-layout>
