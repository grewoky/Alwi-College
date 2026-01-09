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
          <div class="flex items-center rounded-lg border bg-white shadow-inner focus-within:ring-2 focus-within:ring-blue-600">
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   placeholder="Masukkan password baru"
                   class="flex-1 px-3 py-2 outline-none bg-transparent focus:outline-none"/>
            <button type="button" id="togglePassword" class="flex items-center justify-center w-10 h-10 text-gray-500 hover:text-gray-700 transition-colors focus:outline-none"
              aria-label="Toggle password visibility">
              <!-- Eye Open Icon -->
              <svg id="eyeOpenIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
              </svg>
              <!-- Eye Closed Icon -->
              <svg id="eyeClosedIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                <line x1="1" y1="1" x2="23" y2="23"></line>
              </svg>
            </button>
          </div>
          @error('password')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
        </div>

        {{-- Confirm Password --}}
        <div class="space-y-1">
          <label for="password_confirmation" class="text-sm font-medium text-gray-700">Konfirmasi Password</label>
          <div class="flex items-center rounded-lg border bg-white shadow-inner focus-within:ring-2 focus-within:ring-blue-600">
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   placeholder="Konfirmasi password baru"
                   class="flex-1 px-3 py-2 outline-none bg-transparent focus:outline-none"/>
            <button type="button" id="togglePasswordConfirm" class="flex items-center justify-center w-10 h-10 text-gray-500 hover:text-gray-700 transition-colors focus:outline-none"
              aria-label="Toggle password confirmation visibility">
              <!-- Eye Open Icon -->
              <svg id="eyeOpenIconConfirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
              </svg>
              <!-- Eye Closed Icon -->
              <svg id="eyeClosedIconConfirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                <line x1="1" y1="1" x2="23" y2="23"></line>
              </svg>
            </button>
          </div>
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

<!-- Password Visibility Toggle Script -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Toggle for Password field
    const togglePasswordBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeOpenIcon = document.getElementById('eyeOpenIcon');
    const eyeClosedIcon = document.getElementById('eyeClosedIcon');

    if (togglePasswordBtn && passwordInput) {
      togglePasswordBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        eyeOpenIcon.classList.toggle('hidden');
        eyeClosedIcon.classList.toggle('hidden');
      });
    }

    // Toggle for Password Confirmation field
    const togglePasswordConfirmBtn = document.getElementById('togglePasswordConfirm');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    const eyeOpenIconConfirm = document.getElementById('eyeOpenIconConfirm');
    const eyeClosedIconConfirm = document.getElementById('eyeClosedIconConfirm');

    if (togglePasswordConfirmBtn && passwordConfirmInput) {
      togglePasswordConfirmBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const isPassword = passwordConfirmInput.type === 'password';
        passwordConfirmInput.type = isPassword ? 'text' : 'password';
        eyeOpenIconConfirm.classList.toggle('hidden');
        eyeClosedIconConfirm.classList.toggle('hidden');
      });
    }
  });
</script>
