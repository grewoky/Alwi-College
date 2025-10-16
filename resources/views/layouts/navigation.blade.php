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
@endphp

@auth
  {{-- ADMIN MENU --}}
  @if(in_array('admin',$roles))
    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">Dashboard</x-nav-link>
    <x-nav-link :href="route('lessons.generate.form')" :active="request()->routeIs('lessons.generate.form')">Generate Jadwal</x-nav-link>
    <x-nav-link :href="route('trips.index')" :active="request()->routeIs('trips.index')">Rekap Trip</x-nav-link>
    <x-nav-link :href="route('pay.list')" :active="request()->routeIs('pay.list')">Verifikasi Pembayaran</x-nav-link>
    <x-nav-link :href="route('info.list')" :active="request()->routeIs('info.list')">Info Siswa</x-nav-link>
  @endif

  {{-- TEACHER MENU --}}
  @if(in_array('teacher',$roles))
    <x-nav-link :href="route('teacher.dashboard')" :active="request()->routeIs('teacher.dashboard')">Dashboard</x-nav-link>
    <x-nav-link :href="route('teacher.lessons')" :active="request()->routeIs('teacher.lessons')">Jadwal Mengajar</x-nav-link>
    <x-nav-link :href="route('info.list')" :active="request()->routeIs('info.list')">Info Siswa</x-nav-link>
  @endif

  {{-- STUDENT MENU --}}
  @if(in_array('student',$roles))
    <x-nav-link :href="route('student.dashboard')" :active="request()->routeIs('student.dashboard')">Dashboard</x-nav-link>
    <x-nav-link :href="route('info.index')" :active="request()->routeIs('info.index')">Info (Upload)</x-nav-link>
    <x-nav-link :href="route('pay.index')" :active="request()->routeIs('pay.index')">Pembayaran</x-nav-link>
  @endif
@else
  <x-nav-link :href="route('login')" :active="request()->routeIs('login')">Login</x-nav-link>
@endauth
