<x-guest-layout>
  <div class="w-full max-w-md">
    {{-- Card --}}
    <div class="bg-white/90 backdrop-blur border border-gray-200 rounded-2xl shadow-sm p-6 md:p-8">
      {{-- Title --}}
      <div class="text-center mb-6">
        <h1 class="text-xl md:text-2xl font-semibold">Reset Password</h1>
        <p class="text-gray-600 text-sm mt-2">Buat password baru untuk akun Anda</p>
        <div class="mt-3 h-px bg-gray-200"></div>
      </div>

      {{-- Errors --}}
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
      <form method="POST" action="{{ route('password.store') }}" class="space-y-3">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        {{-- Email --}}
        <div class="space-y-1">
          <label for="email" class="text-sm font-medium text-gray-700">Email</label>
          <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition">
          @error('email')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
        </div>

        {{-- Password --}}
        <div class="space-y-1">
          <label for="password" class="text-sm font-medium text-gray-700">Password Baru</label>
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
          Reset Password
        </button>
      </form>
    </div>
  </div>
</x-guest-layout>
