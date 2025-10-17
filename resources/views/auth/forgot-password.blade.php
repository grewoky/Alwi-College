<x-guest-layout>
  <div class="w-full max-w-md">
    {{-- Card --}}
    <div class="bg-white/90 backdrop-blur border border-gray-200 rounded-2xl shadow-sm p-6 md:p-8">
      {{-- Title --}}
      <div class="text-center mb-6">
        <h1 class="text-xl md:text-2xl font-semibold">Lupa Password?</h1>
        <p class="text-gray-600 text-sm mt-2">Masukkan email Anda dan kami akan mengirimkan link reset password</p>
        <div class="mt-3 h-px bg-gray-200"></div>
      </div>

      {{-- Status --}}
      @if (session('status'))
        <div class="mb-4 p-3 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg">
          {{ session('status') }}
        </div>
      @endif

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
      <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        {{-- Email --}}
        <div class="space-y-1">
          <label for="email" class="text-sm font-medium text-gray-700">Email</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition">
          @error('email')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
        </div>

        {{-- Submit --}}
        <button type="submit" class="w-full mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
          Kirim Link Reset
        </button>

        {{-- Back to Login --}}
        <div class="text-center">
          <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">← Kembali ke Login</a>
        </div>
      </form>
    </div>
  </div>
</x-guest-layout>
