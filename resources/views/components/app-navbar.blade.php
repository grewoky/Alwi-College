{{-- Navbar Component untuk STUDENT & TEACHER --}}
<nav class="bg-[#2E529F] border-b border-[#2E529F]/20 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo dan Brand -->
            <div class="flex items-center gap-3">
                <a href="{{ auth()->check() ? route('dashboard') : url('/') }}" class="flex items-center gap-3">
                    <div class="flex items-center justify-center">
                        <img src="{{ asset('images/logo.png') }}" alt="Alwi College Logo" class="h-8 w-auto">
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-white">Alwi College</h1>
                    </div>
                </a>
            </div>

            <!-- Desktop Navigation -->
                    @php
                        use Illuminate\Support\Facades\DB;
                        $u = auth()->user();
                        $roles = [];
                        if ($u) {
                            $roles = DB::table('model_has_roles')
                                ->join('roles','roles.id','=','model_has_roles.role_id')
                                ->where('model_has_roles.model_type', get_class($u))
                                ->where('model_has_roles.model_id', $u->id)
                                ->pluck('roles.name')->toArray();
                        }
                    @endphp
                    <div class="hidden md:flex items-center gap-1">
                        <a href="{{ route('dashboard') }}" 
                           class="px-4 py-2 rounded-md text-white hover:bg-white/10 transition-colors {{ request()->routeIs('student.dashboard', 'teacher.dashboard') ? 'bg-white/20 font-semibold' : '' }}">
                            Dashboard
                        </a>
                        @if(in_array('student', $roles))
                            <a href="{{ route('lessons.student') }}" 
                               class="px-4 py-2 rounded-md text-white hover:bg-white/10 transition-colors {{ request()->routeIs('lessons.student') ? 'bg-white/20 font-semibold' : '' }}">
                                Jadwal
                            </a>
                            <a href="{{ route('info.index') }}" 
                               class="px-4 py-2 rounded-md text-white hover:bg-white/10 transition-colors {{ request()->routeIs('info.index') ? 'bg-white/20 font-semibold' : '' }}">
                                Info
                            </a>
                            <a href="{{ route('attendance.student') }}" 
                               class="px-4 py-2 rounded-md text-white hover:bg-white/10 transition-colors {{ request()->routeIs('attendance.student') ? 'bg-white/20 font-semibold' : '' }}">
                                Absensi
                            </a>
                            <a href="{{ route('pay.index') }}" 
                               class="px-4 py-2 rounded-md text-white hover:bg-white/10 transition-colors {{ request()->routeIs('pay.index') ? 'bg-white/20 font-semibold' : '' }}">
                                Pembayaran
                            </a>
                        @elseif(in_array('teacher', $roles))
                            <a href="{{ route('lessons.teacher') }}" 
                               class="px-4 py-2 rounded-md text-white hover:bg-white/10 transition-colors {{ request()->routeIs('lessons.teacher') ? 'bg-white/20 font-semibold' : '' }}">
                                Jadwal Mengajar
                            </a>
                            <a href="{{ route('info.teacher.student-files') }}"
                               class="px-4 py-2 rounded-md text-white hover:bg-white/10 transition-colors {{ request()->routeIs('info.teacher.*') ? 'bg-white/20 font-semibold' : '' }}">
                                Info File
                            </a>
                            <a href="{{ route('attendance.teacher') }}" 
                               class="px-4 py-2 rounded-md text-white hover:bg-white/10 transition-colors {{ request()->routeIs('attendance.teacher') ? 'bg-white/20 font-semibold' : '' }}">
                                Absensi
                            </a>
                        @endif
                    </div>

            <!-- Right Side: User Profile Dropdown -->
            <div class="flex md:flex items-center">
                <x-user-profile-dropdown />
            </div>

            <!-- Mobile Menu Button -->
            <button class="md:hidden text-white/90 hover:text-white p-2 rounded-md ml-2" id="mobile-menu-btn">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-white/10">
            <a href="{{ route('dashboard') }}" 
               class="block px-4 py-2 text-white hover:bg-white/10 rounded-md {{ request()->routeIs('student.dashboard', 'teacher.dashboard') ? 'bg-white/20 font-semibold' : '' }}">
                Dashboard
            </a>
            @if(in_array('student', $roles))
                <a href="{{ route('lessons.student') }}" 
                   class="block px-4 py-2 text-white hover:bg-white/10 rounded-md {{ request()->routeIs('lessons.student') ? 'bg-white/20 font-semibold' : '' }}">
                    Jadwal
                </a>
                <a href="{{ route('info.index') }}" 
                   class="block px-4 py-2 text-white hover:bg-white/10 rounded-md {{ request()->routeIs('info.index') ? 'bg-white/20 font-semibold' : '' }}">
                    Info
                </a>
                <a href="{{ route('attendance.student') }}" 
                   class="block px-4 py-2 text-white hover:bg-white/10 rounded-md {{ request()->routeIs('attendance.student') ? 'bg-white/20 font-semibold' : '' }}">
                    Absensi
                </a>
                <a href="{{ route('pay.index') }}" 
                   class="block px-4 py-2 text-white hover:bg-white/10 rounded-md {{ request()->routeIs('pay.index') ? 'bg-white/20 font-semibold' : '' }}">
                    Pembayaran
                </a>
            @elseif(in_array('teacher', $roles))
                <a href="{{ route('lessons.teacher') }}" 
                   class="block px-4 py-2 text-white hover:bg-white/10 rounded-md {{ request()->routeIs('lessons.teacher') ? 'bg-white/20 font-semibold' : '' }}">
                    Jadwal Mengajar
                </a>
                <a href="{{ route('info.teacher.student-files') }}"
                   class="block px-4 py-2 text-white hover:bg-white/10 rounded-md {{ request()->routeIs('info.teacher.*') ? 'bg-white/20 font-semibold' : '' }}">
                    Info File
                </a>
                <a href="{{ route('attendance.teacher') }}" 
                   class="block px-4 py-2 text-white hover:bg-white/10 rounded-md {{ request()->routeIs('attendance.teacher') ? 'bg-white/20 font-semibold' : '' }}">
                    Absensi
                </a>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="mt-4 px-4">
                @csrf
                <button type="submit" class="block px-4 py-2 rounded-md bg-red-600 hover:bg-red-700 text-white shadow-sm text-sm font-medium transition">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<script>
    (function(){
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        if (!btn || !menu) return;
        btn.setAttribute('aria-expanded', 'false');

        btn.addEventListener('click', function(e) {
            const isHidden = menu.classList.contains('hidden');
            if (isHidden) {
                menu.classList.remove('hidden');
                btn.setAttribute('aria-expanded', 'true');
            } else {
                menu.classList.add('hidden');
                btn.setAttribute('aria-expanded', 'false');
            }
        });

        // Close when clicking outside on small screens
        document.addEventListener('click', function(e){
            const target = e.target;
            if (!menu.classList.contains('hidden') && !menu.contains(target) && !btn.contains(target)) {
                menu.classList.add('hidden');
                btn.setAttribute('aria-expanded', 'false');
            }
        });
    })();
</script>
