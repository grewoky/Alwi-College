<x-guest-layout>
  <div class="w-full max-w-md">
    {{-- Card --}}
    <div class="bg-white/90 backdrop-blur border border-gray-200 rounded-2xl shadow-sm p-6 md:p-8">
      {{-- Logo + Title --}}
      <div class="text-center mb-6">
        <div class="mx-auto mb-2 h-8 w-8 rounded-full bg-blue-600/90 flex items-center justify-center shadow-sm">
          {{-- bintang/kompas --}}
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2l1.9 6.2L20 10l-6.1 1.8L12 18l-1.9-6.2L4 10l6.1-1.8L12 2z"/>
          </svg>
        </div>
        <div class="font-semibold">Alwi College</div>
        <h1 class="text-xl md:text-2xl font-semibold mt-1">Daftar Akun</h1>
        <div class="mt-2 h-px bg-gray-200"></div>
      </div>

      {{-- Status / Errors --}}
      @if ($errors->any())
        <div class="mb-3 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg px-3 py-2">
          <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- Form --}}
      <form method="POST" action="{{ route('register') }}" class="space-y-3">
        @csrf

        {{-- Nama --}}
        <div class="space-y-1">
          <label for="name" class="text-sm font-medium text-gray-700">Nama Lengkap</label>
          <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" 
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition">
          @error('name')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
        </div>

        {{-- Email --}}
        <div class="space-y-1">
          <label for="email" class="text-sm font-medium text-gray-700">Email</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition">
          @error('email')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
        </div>

        {{-- Password --}}
        <div class="space-y-1">
          <label for="password" class="text-sm font-medium text-gray-700">Password</label>
          <input id="password" type="password" name="password" required autocomplete="new-password"
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition">
          @error('password')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
        </div>

        {{-- Confirm Password --}}
        <div class="space-y-1">
          <label for="password_confirmation" class="text-sm font-medium text-gray-700">Konfirmasi Password</label>
          <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition">
          @error('password_confirmation')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
        </div>

        {{-- Submit --}}
        <button type="submit" class="w-full mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
          Daftar Sekarang
        </button>

        {{-- Link ke Login --}}
        <div class="text-center mt-4">
          <p class="text-sm text-gray-600">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-700">Login di sini</a>
          </p>
        </div>
      </form>
    </div>
  </div>
</x-guest-layout>
