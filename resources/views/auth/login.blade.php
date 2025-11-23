<x-guest-layout>
  <div class="w-full max-w-md">
    {{-- Card --}}
    <div class="bg-white/90 backdrop-blur border border-gray-200 rounded-2xl shadow-sm p-6 md:p-8">
      {{-- Logo + Title --}}
      <div class="text-center mb-6">
        <div class="mx-auto mb-2 h-16 w-16 flex items-center justify-center">
          <img src="{{ asset('images/logo.png') }}" alt="Alwi College Logo" class="w-full h-full object-contain">
        </div>
        <div class="font-semibold">Alwi College</div>
        <h1 class="text-xl md:text-2xl font-semibold mt-1">Login</h1>
        <div class="mt-2 h-px bg-gray-200"></div>
      </div>

      {{-- Status / Errors --}}
      @if (session('status'))
        <div class="mb-3 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg px-3 py-2">
          {{ session('status') }}
        </div>
      @endif
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
      <form method="POST" action="{{ route('login') }}" class="space-y-3">
        @csrf

        {{-- Email --}}
        <div class="space-y-1">
          <label for="email" class="text-sm font-medium text-gray-700">Email</label>
          <div class="flex items-center rounded-lg border bg-white shadow-inner">
            <div class="flex items-center justify-center w-10 h-10 rounded-l-lg bg-blue-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 4H4a2 2 0 00-2 2v.01l10 6.25L22 6.01V6a2 2 0 00-2-2z"/>
                <path d="M22 8.24l-10 6.25L2 8.24V18a2 2 0 002 2h16a2 2 0 002-2V8.24z"/>
              </svg>
            </div>
            <input id="email" name="email" type="email" required autofocus
                   placeholder="Masukkan email anda"
                   value="{{ old('email') }}"
                   class="w-full h-10 px-3 outline-none bg-transparent rounded-r-lg"/>
          </div>
        </div>

        {{-- Password --}}
        <div class="space-y-1">
          <div class="flex items-center justify-between">
            <label for="password" class="text-sm font-medium text-gray-700">Password</label>
            @if (Route::has('password.request'))
              <a class="text-xs text-gray-500 hover:text-gray-700" href="{{ route('password.request') }}">
                Lupa password?
              </a>
            @endif
          </div>
          <div class="flex items-center rounded-lg border bg-white shadow-inner">
            <div class="flex items-center justify-center w-10 h-10 rounded-l-lg bg-blue-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 1a5 5 0 00-5 5v3H6a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2v-8a2 2 0 00-2-2h-1V6a5 5 0 00-5-5zm-3 8V6a3 3 0 116 0v3H9z"/>
              </svg>
            </div>
            <input id="password" name="password" type="password" required
                   placeholder="Masukkan password anda"
                   class="w-full h-10 px-3 outline-none bg-transparent rounded-r-lg"/>
          </div>
        </div>

        {{-- Remember --}}
        <label class="flex items-center gap-2 text-sm text-gray-700 select-none">
          <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
          Ingat saya
        </label>

        {{-- Button --}}
        <button type="submit"
                class="w-full h-10 rounded-lg bg-blue-600 text-white font-medium shadow hover:bg-blue-700 transition">
          Masuk
        </button>
      
        {{-- Info pendaftaran ditutup publik --}}
        <div class="text-center mt-4">
          <p class="text-sm text-gray-600">Untuk membuat akun baru, silakan hubungi administrator.</p>
        </div>
      </form>
    </div>
  </div>
</x-guest-layout>

<!-- Floating WhatsApp Button on Login page -->
<div class="fixed bottom-6 right-6 z-50 group w-14 h-14" style="will-change: transform, opacity;">
  <div class="w-full h-full flex items-center justify-center">
    <a href="https://wa.me/6282179970473" target="_blank" rel="noopener noreferrer"
      class="w-12 h-12 bg-green-500 hover:bg-green-600 text-white rounded-full shadow-lg transition-all duration-200 hover:scale-105 transform-gpu flex items-center justify-center btn-glow"
      aria-label="Chat on WhatsApp">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.652a11.944 11.944 0 005.705 1.452h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>
  </div>
  <span role="tooltip" class="absolute bottom-full mb-3 left-1/2 -translate-x-1/2 -translate-y-2 whitespace-nowrap bg-gray-900 text-white text-sm px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transform transition-all duration-200 pointer-events-none shadow-lg">
    Customer Service
  </span>
</div>
