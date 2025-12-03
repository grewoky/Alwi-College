<nav class="bg-white shadow-sm">
  <div class="container mx-auto px-4 py-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
      <a href="{{ auth()->check() ? route('dashboard') : url('/') }}" class="flex items-center gap-3">
        <img src="{{ asset('images/logo.png') }}" alt="logo" class="h-9 w-auto">
        <span class="text-lg font-bold text-primary">Alwi College</span>
      </a>
    </div>

    <div class="hidden md:flex items-center gap-4">
      <a href="#about" class="text-gray-700 hover:text-primary">Tentang</a>
      <a href="#features" class="text-gray-700 hover:text-primary">Fitur</a>
      <a href="#" class="text-gray-700 hover:text-primary">Kontak</a>
      @if(Route::has('login'))
        @auth
          <a href="{{ route('dashboard') }}" class="ml-4 inline-flex items-center px-4 py-2 rounded-lg bg-primary text-white">Dashboard</a>
        @else
          <a href="{{ route('login') }}" class="ml-4 inline-flex items-center px-4 py-2 rounded-lg border border-primary text-primary">Login</a>
        @endauth
      @endif
    </div>

    <!-- Mobile menu button -->
    <div class="md:hidden">
      <button id="mobile-menu-btn" class="p-2 rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
    </div>
  </div>

  <div id="mobile-menu" class="hidden md:hidden border-t">
    <div class="px-4 py-4 space-y-2">
      <a href="#about" class="block text-gray-700">Tentang</a>
      <a href="#features" class="block text-gray-700">Fitur</a>
      <a href="#" class="block text-gray-700">Kontak</a>
      @if(Route::has('login'))
        @auth
          <a href="{{ route('dashboard') }}" class="block text-primary font-semibold">Dashboard</a>
        @else
          <a href="{{ route('login') }}" class="block text-primary font-semibold">Login</a>
        @endauth
      @endif
    </div>
  </div>

  <script>
    document.getElementById('mobile-menu-btn')?.addEventListener('click', function(){
      const m = document.getElementById('mobile-menu');
      if(m) m.classList.toggle('hidden');
    });
  </script>
</nav>
{{-- Navbar Component untuk Landing Page --}}
<nav class="bg-white border-b border-gray-200 shadow-sm fixed w-full top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo dan Brand -->
            <div class="flex items-center gap-3">
                <a href="{{ auth()->check() ? route('dashboard') : url('/') }}" class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-md flex items-center justify-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Alwi College Logo" class="w-10 h-10 object-contain">
                  </div>
                  <div>
                    <h1 class="text-lg font-bold text-gray-900">Alwi College</h1>
                  </div>
                </a>
            </div>

            <!-- Login Button -->
            <div>
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Login
                </a>
            </div>
        </div>
    </div>
</nav>