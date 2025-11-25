@php
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\DB;
  $u = Auth::user();
  $roles = [];
  if ($u) {
    $roles = DB::table('model_has_roles')
      ->join('roles','roles.id','=','model_has_roles.role_id')
      ->where('model_has_roles.model_type', get_class($u))
      ->where('model_has_roles.model_id', $u->id)
      ->pluck('roles.name')->toArray();
  }
  function nav($href,$label,$active=false){ return ['href'=>$href,'label'=>$label,'active'=>$active]; }
  $items = [];
  if (in_array('admin',$roles)) {
    $items = [
      nav(route('admin.dashboard'),'Dashboard', request()->routeIs('admin.*') || request()->routeIs('trips.*') || request()->routeIs('lessons.*') || request()->routeIs('pay.list')),
      nav(route('lessons.generate.form'),'Generate Jadwal', request()->routeIs('lessons.generate.*')),
      nav(route('trips.index'),'Rekap Trip', request()->routeIs('trips.index')),
      nav(route('pay.list'),'Verifikasi Pembayaran', request()->routeIs('pay.list')),
      nav(route('info.admin.list'),'Info Siswa', request()->routeIs('info.admin.*') || request()->routeIs('info.download') || request()->routeIs('info.download.*')),
    ];
  }
  if (in_array('teacher',$roles)) {
    $items = [
      nav(route('teacher.dashboard'),'Dashboard', request()->routeIs('teacher.*')),
      nav(route('teacher.lessons'),'Jadwal Mengajar', request()->routeIs('teacher.lessons') || request()->routeIs('attendance.*')),
      nav(route('info.teacher.student-files'),'Info Siswa', request()->routeIs('info.teacher.*') || request()->routeIs('info.teacher.student-files')),
    ];
  }
  if (in_array('student',$roles)) {
    $items = [
      nav(route('student.dashboard'),'Dashboard', request()->routeIs('student.*')),
      nav(route('info.index'),'Info (Upload)', request()->routeIs('info.index')),
      nav(route('pay.index'),'Pembayaran', request()->routeIs('pay.index')),
    ];
  }
@endphp

<nav class="bg-white border rounded-2xl p-3 shadow-sm">
  <div class="text-xs uppercase tracking-wide text-gray-500 px-2 mb-2">Menu</div>
  <ul class="space-y-1">
    @foreach($items as $it)
      <li>
        <a href="{{ $it['href'] }}"
           class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm
           {{ $it['active'] ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'text-gray-700 hover:bg-gray-50' }}">
          <span>{{ $it['label'] }}</span>
        </a>
      </li>
    @endforeach
  </ul>
</nav>
