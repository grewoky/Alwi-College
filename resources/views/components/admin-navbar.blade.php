{{-- Navbar Component untuk ADMIN --}}
<nav class="text-white border-b border-white/10 shadow-md" style="background: linear-gradient(90deg, #1B2A6B 0%, #3C5EC7 100%);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo dan Brand -->
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Alwi College Logo" class="h-8 w-auto">
                </div>
                <div>
                    <h1 class="text-lg font-bold text-white">Alwi College</h1>
                </div>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center gap-1">
                <a href="{{ route('admin.dashboard') }}" 
                   class="px-4 py-2 rounded-md text-white hover:bg-white/10 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 font-semibold' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('lessons.admin.dashboard') }}" 
                   class="px-4 py-2 rounded-md text-white hover:bg-white/10 transition-colors {{ request()->routeIs('lessons.admin.dashboard', 'lessons.admin', 'lessons.generate.form', 'lessons.edit') ? 'bg-white/20 font-semibold' : '' }}">
                    Jadwal
                </a>
                <a href="{{ route('info.admin.list') }}" 
                   class="px-4 py-2 rounded-md text-white hover:bg-white/10 transition-colors {{ request()->routeIs('info.admin.list') ? 'bg-white/20 font-semibold' : '' }}">
                    Info File
                </a>
                <a href="{{ route('trips.index') }}" 
                   class="px-4 py-2 rounded-md text-white hover:bg-white/10 transition-colors {{ request()->routeIs('trips.index', 'trips.show') ? 'bg-white/20 font-semibold' : '' }}">
                    Trip Guru
                </a>
                <a href="{{ route('pay.list') }}" 
                   class="px-4 py-2 rounded-md text-white hover:bg-white/10 transition-colors {{ request()->routeIs('pay.list') ? 'bg-white/20 font-semibold' : '' }}">
                    Pembayaran
                </a>
            </div>

            <!-- Right Side: User Info & Logout -->
            <div class="hidden md:flex items-center gap-4">
                <div class="text-right">
                    <p class="text-sm font-medium text-white">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-xs text-white/80">Administrator</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-white/90 hover:text-white transition-colors text-sm font-medium">
                        Logout
                    </button>
                </form>
            </div>

            <!-- Mobile Menu Button -->
            <button class="md:hidden text-white/90 hover:text-white p-2 rounded-md" id="mobile-menu-btn">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-white/10">
            <a href="{{ route('admin.dashboard') }}" 
               class="block px-4 py-2 text-white hover:bg-white/10 rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 font-semibold' : '' }}">
                Dashboard
            </a>
            <a href="{{ route('lessons.admin.dashboard') }}" 
               class="block px-4 py-2 text-white hover:bg-white/10 rounded-md {{ request()->routeIs('lessons.admin.dashboard', 'lessons.admin', 'lessons.generate.form', 'lessons.edit') ? 'bg-white/20 font-semibold' : '' }}">
                Jadwal
            </a>
            <a href="{{ route('info.admin.list') }}" 
               class="block px-4 py-2 text-white hover:bg-white/10 rounded-md {{ request()->routeIs('info.admin.list') ? 'bg-white/20 font-semibold' : '' }}">
                Info File
            </a>
            <a href="{{ route('trips.index') }}" 
               class="block px-4 py-2 text-white hover:bg-white/10 rounded-md {{ request()->routeIs('trips.index', 'trips.show') ? 'bg-white/20 font-semibold' : '' }}">
                Trip Guru
            </a>
            <a href="{{ route('pay.list') }}" 
               class="block px-4 py-2 text-white hover:bg-white/10 rounded-md {{ request()->routeIs('pay.list') ? 'bg-white/20 font-semibold' : '' }}">
                Pembayaran
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-4 px-4">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 text-white hover:bg-white/10 rounded-md text-sm font-medium">
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
