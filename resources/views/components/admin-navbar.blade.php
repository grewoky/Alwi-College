{{-- Navbar Component untuk ADMIN --}}
<nav class="bg-gradient-to-r from-indigo-600 to-indigo-800 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo dan Brand -->
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center font-bold text-indigo-600">
                    AC
                </div>
                <span class="text-white text-xl font-bold">Alwi College Admin</span>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center gap-1">
                <a href="{{ route('admin.dashboard') }}" 
                   class="px-4 py-2 rounded-lg text-white transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-900 font-semibold' : 'hover:bg-indigo-700' }}">
                    📊 Dashboard
                </a>
                <a href="{{ route('lessons.admin.dashboard') }}" 
                   class="px-4 py-2 rounded-lg text-white transition-all duration-200 {{ request()->routeIs('lessons.admin.dashboard', 'lessons.admin', 'lessons.generate.form', 'lessons.edit') ? 'bg-indigo-900 font-semibold' : 'hover:bg-indigo-700' }}">
                    📚 Jadwal
                </a>
                <a href="{{ route('info.admin.list') }}" 
                   class="px-4 py-2 rounded-lg text-white transition-all duration-200 {{ request()->routeIs('info.admin.list') ? 'bg-indigo-900 font-semibold' : 'hover:bg-indigo-700' }}">
                    📋 Info File
                </a>
                <a href="{{ route('trips.index') }}" 
                   class="px-4 py-2 rounded-lg text-white transition-all duration-200 {{ request()->routeIs('trips.index', 'trips.show') ? 'bg-indigo-900 font-semibold' : 'hover:bg-indigo-700' }}">
                    🚗 Trip Guru
                </a>
                <a href="{{ route('pay.list') }}" 
                   class="px-4 py-2 rounded-lg text-white transition-all duration-200 {{ request()->routeIs('pay.list') ? 'bg-indigo-900 font-semibold' : 'hover:bg-indigo-700' }}">
                    💳 Pembayaran
                </a>
            </div>

            <!-- Right Side: User Info & Logout -->
            <div class="hidden md:flex items-center gap-4">
                <span class="text-white text-sm">Halo, <strong>{{ auth()->user()->name ?? 'Admin' }}</strong></span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-white hover:text-red-200 transition-colors text-sm">
                        Logout
                    </button>
                </form>
            </div>

            <!-- Mobile Menu Button -->
            <button class="md:hidden text-white hover:bg-indigo-700 p-2 rounded-lg" id="mobile-menu-btn">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-indigo-700">
            <a href="{{ route('admin.dashboard') }}" 
               class="block px-4 py-2 text-white hover:bg-indigo-700 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-900 font-semibold' : '' }}">
                📊 Dashboard
            </a>
            <a href="{{ route('lessons.admin.dashboard') }}" 
               class="block px-4 py-2 text-white hover:bg-indigo-700 rounded-lg {{ request()->routeIs('lessons.admin.dashboard', 'lessons.admin', 'lessons.generate.form', 'lessons.edit') ? 'bg-indigo-900 font-semibold' : '' }}">
                📚 Jadwal
            </a>
            <a href="{{ route('info.admin.list') }}" 
               class="block px-4 py-2 text-white hover:bg-indigo-700 rounded-lg {{ request()->routeIs('info.admin.list') ? 'bg-indigo-900 font-semibold' : '' }}">
                📋 Info File
            </a>
            <a href="{{ route('trips.index') }}" 
               class="block px-4 py-2 text-white hover:bg-indigo-700 rounded-lg {{ request()->routeIs('trips.index', 'trips.show') ? 'bg-indigo-900 font-semibold' : '' }}">
                🚗 Trip Guru
            </a>
            <a href="{{ route('pay.list') }}" 
               class="block px-4 py-2 text-white hover:bg-indigo-700 rounded-lg {{ request()->routeIs('pay.list') ? 'bg-indigo-900 font-semibold' : '' }}">
                💳 Pembayaran
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-4 px-4">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 text-white hover:bg-indigo-700 rounded-lg text-sm">
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
