{{-- Navbar Component untuk ADMIN --}}
<nav class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo dan Brand -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 rounded-md flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.5 2 16.5S6.5 26.5 12 26.5s10-4.5 10-10.5c0-5.997-4.5-10.247-10-10.247z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-gray-900">Alwi College</h1>
                    <p class="text-xs text-gray-500">Admin Dashboard</p>
                </div>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center gap-1">
                <a href="{{ route('admin.dashboard') }}" 
                   class="px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('lessons.admin.dashboard') }}" 
                   class="px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 transition-colors {{ request()->routeIs('lessons.admin.dashboard', 'lessons.admin', 'lessons.generate.form', 'lessons.edit') ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
                    Jadwal
                </a>
                <a href="{{ route('info.admin.list') }}" 
                   class="px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 transition-colors {{ request()->routeIs('info.admin.list') ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
                    Info File
                </a>
                <a href="{{ route('trips.index') }}" 
                   class="px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 transition-colors {{ request()->routeIs('trips.index', 'trips.show') ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
                    Trip Guru
                </a>
                <a href="{{ route('pay.list') }}" 
                   class="px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 transition-colors {{ request()->routeIs('pay.list') ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
                    Pembayaran
                </a>
            </div>

            <!-- Right Side: User Info & Logout -->
            <div class="hidden md:flex items-center gap-4">
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-xs text-gray-500">Administrator</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-gray-600 hover:text-gray-900 transition-colors text-sm">
                        Logout
                    </button>
                </form>
            </div>

            <!-- Mobile Menu Button -->
            <button class="md:hidden text-gray-600 hover:text-gray-900 p-2 rounded-md" id="mobile-menu-btn">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-gray-200">
            <a href="{{ route('admin.dashboard') }}" 
               class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
                Dashboard
            </a>
            <a href="{{ route('lessons.admin.dashboard') }}" 
               class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md {{ request()->routeIs('lessons.admin.dashboard', 'lessons.admin', 'lessons.generate.form', 'lessons.edit') ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
                Jadwal
            </a>
            <a href="{{ route('info.admin.list') }}" 
               class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md {{ request()->routeIs('info.admin.list') ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
                Info File
            </a>
            <a href="{{ route('trips.index') }}" 
               class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md {{ request()->routeIs('trips.index', 'trips.show') ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
                Trip Guru
            </a>
            <a href="{{ route('pay.list') }}" 
               class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md {{ request()->routeIs('pay.list') ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
                Pembayaran
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-4 px-4">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md text-sm">
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
