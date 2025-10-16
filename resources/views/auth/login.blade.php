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
      </form>
    </div>
  </div>
</x-guest-layout>
