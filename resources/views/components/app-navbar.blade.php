{{-- Navbar Component untuk STUDENT & TEACHER --}}
<nav class="bg-gradient-to-r from-blue-600 to-blue-800 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo dan Brand -->
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center font-bold text-blue-600">
                    AC
                </div>
                <span class="text-white text-xl font-bold">Alwi College</span>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center gap-2">
                <a href="{{ route('dashboard') }}" 
                   class="px-4 py-2 rounded-lg text-white transition-all duration-200 {{ request()->routeIs('student.dashboard', 'teacher.dashboard') ? 'bg-blue-900 font-semibold' : 'hover:bg-blue-700' }}">
                    📊 Dashboard
                </a>
                @if(auth()->user() && auth()->user()->hasRole('student'))
                    <a href="{{ route('lessons.student') }}" 
                       class="px-4 py-2 rounded-lg text-white transition-all duration-200 {{ request()->routeIs('lessons.student') ? 'bg-blue-900 font-semibold' : 'hover:bg-blue-700' }}">
                        📅 Jadwal Les
                    </a>
                    <a href="{{ route('info.index') }}" 
                       class="px-4 py-2 rounded-lg text-white transition-all duration-200 {{ request()->routeIs('info.index') ? 'bg-blue-900 font-semibold' : 'hover:bg-blue-700' }}">
                        📋 Info
                    </a>
                    <a href="{{ route('attendance.student') }}" 
                       class="px-4 py-2 rounded-lg text-white transition-all duration-200 {{ request()->routeIs('attendance.student') ? 'bg-blue-900 font-semibold' : 'hover:bg-blue-700' }}">
                        ✓ Absensi
                    </a>
                    <a href="{{ route('pay.index') }}" 
                       class="px-4 py-2 rounded-lg text-white transition-all duration-200 {{ request()->routeIs('pay.index') ? 'bg-blue-900 font-semibold' : 'hover:bg-blue-700' }}">
                        💳 Pembayaran
                    </a>
                @elseif(auth()->user() && auth()->user()->hasRole('teacher'))
                    <a href="{{ route('lessons.teacher') }}" 
                       class="px-4 py-2 rounded-lg text-white transition-all duration-200 {{ request()->routeIs('lessons.teacher') ? 'bg-blue-900 font-semibold' : 'hover:bg-blue-700' }}">
                        📅 Jadwal Mengajar
                    </a>
                    <a href="{{ route('attendance.teacher') }}" 
                       class="px-4 py-2 rounded-lg text-white transition-all duration-200 {{ request()->routeIs('attendance.teacher') ? 'bg-blue-900 font-semibold' : 'hover:bg-blue-700' }}">
                        ✓ Absensi
                    </a>
                @endif
            </div>

            <!-- Right Side: User Info & Logout -->
            <div class="hidden md:flex items-center gap-4">
                <span class="text-white text-sm">Halo, <strong>{{ auth()->user()->name ?? 'User' }}</strong></span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-white hover:text-red-200 transition-colors">
                        Logout
                    </button>
                </form>
            </div>

            <!-- Mobile Menu Button -->
            <button class="md:hidden text-white hover:bg-blue-700 p-2 rounded-lg" id="mobile-menu-btn">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-blue-700">
            <a href="{{ route('dashboard') }}" 
               class="block px-4 py-2 text-white hover:bg-blue-700 rounded-lg {{ request()->routeIs('student.dashboard', 'teacher.dashboard') ? 'bg-blue-900 font-semibold' : '' }}">
                📊 Dashboard
            </a>
            @if(auth()->user() && auth()->user()->hasRole('student'))
                <a href="{{ route('lessons.student') }}" 
                   class="block px-4 py-2 text-white hover:bg-blue-700 rounded-lg {{ request()->routeIs('lessons.student') ? 'bg-blue-900 font-semibold' : '' }}">
                    📅 Jadwal Les
                </a>
                <a href="{{ route('info.index') }}" 
                   class="block px-4 py-2 text-white hover:bg-blue-700 rounded-lg {{ request()->routeIs('info.index') ? 'bg-blue-900 font-semibold' : '' }}">
                    📋 Info
                </a>
                <a href="{{ route('attendance.student') }}" 
                   class="block px-4 py-2 text-white hover:bg-blue-700 rounded-lg {{ request()->routeIs('attendance.student') ? 'bg-blue-900 font-semibold' : '' }}">
                    ✓ Absensi
                </a>
                <a href="{{ route('pay.index') }}" 
                   class="block px-4 py-2 text-white hover:bg-blue-700 rounded-lg {{ request()->routeIs('pay.index') ? 'bg-blue-900 font-semibold' : '' }}">
                    💳 Pembayaran
                </a>
            @elseif(auth()->user() && auth()->user()->hasRole('teacher'))
                <a href="{{ route('lessons.teacher') }}" 
                   class="block px-4 py-2 text-white hover:bg-blue-700 rounded-lg {{ request()->routeIs('lessons.teacher') ? 'bg-blue-900 font-semibold' : '' }}">
                    📅 Jadwal Mengajar
                </a>
                <a href="{{ route('attendance.teacher') }}" 
                   class="block px-4 py-2 text-white hover:bg-blue-700 rounded-lg {{ request()->routeIs('attendance.teacher') ? 'bg-blue-900 font-semibold' : '' }}">
                    ✓ Absensi
                </a>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="mt-4 px-4">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 text-white hover:bg-blue-700 rounded-lg">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<script>
    document.getElementById('mobile-menu-btn').addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });
</script>
