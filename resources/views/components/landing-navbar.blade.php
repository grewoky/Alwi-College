{{-- Navbar Component untuk Landing Page --}}
<nav class="bg-white border-b border-gray-200 shadow-sm fixed w-full top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo dan Brand -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-md flex items-center justify-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Alwi College Logo" class="w-10 h-10 object-contain">
                </div>
                <div>
                    <h1 class="text-lg font-bold text-gray-900">Alwi College</h1>
                </div>
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