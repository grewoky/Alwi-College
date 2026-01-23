<x-guest-layout>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-50 via-white to-blue-50 px-4 py-6 sm:py-8 lg:py-12">
    <div class="w-full max-w-md">
      {{-- Card --}}
      <div class="bg-white/90 backdrop-blur-sm border border-gray-200 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 sm:p-8">
        {{-- Logo + Title --}}
        <div class="text-center mb-8">
          <div class="mx-auto mb-3 h-16 w-16 sm:h-20 sm:w-20 flex items-center justify-center">
            <img src="{{ asset('images/logo.png') }}" alt="Alwi College Logo" class="w-full h-full object-contain">
          </div>
          <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Alwi College</h2>
          <h1 class="text-2xl sm:text-3xl font-bold mt-2 text-[#2E529F]">Login</h1>
          <div class="mt-3 h-1 w-20 sm:w-24 mx-auto rounded-full bg-gradient-to-r from-[#3B63B5] via-[#6FA2FF] to-[#3B63B5] opacity-40"></div>
        </div>

        {{-- Status / Errors --}}
        @if (session('status'))
          <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg px-4 py-3 animate-in fade-in duration-300">
            {{ session('status') }}
          </div>
        @endif
        @if ($errors->any())
          <div class="mb-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg px-4 py-3 animate-in fade-in duration-300">
            <ul class="list-disc list-inside space-y-1">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
          @csrf

          {{-- Email --}}
          <div class="space-y-2">
            <label for="email" class="block text-sm font-semibold text-gray-800">Email Address</label>
            <div class="flex items-center rounded-xl border border-gray-300 bg-white hover:border-blue-400 focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-100 transition-all duration-200">
              <div class="flex items-center justify-center px-3 sm:px-4 py-3 text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M20 4H4a2 2 0 00-2 2v.01l10 6.25L22 6.01V6a2 2 0 00-2-2z"/>
                  <path d="M22 8.24l-10 6.25L2 8.24V18a2 2 0 002 2h16a2 2 0 002-2V8.24z"/>
                </svg>
              </div>
              <input id="email" name="email" type="email" required autofocus
                     placeholder="your.email@example.com"
                     value="{{ old('email') }}"
                     class="flex-1 h-12 px-3 sm:px-4 outline-none bg-transparent text-gray-900 placeholder-gray-400 text-sm sm:text-base"/>
            </div>
            @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>

          {{-- Password --}}
          <div class="space-y-2">
            <div class="flex items-center justify-between">
              <label for="password" class="block text-sm font-semibold text-gray-800">Password</label>
              @if (Route::has('password.request'))
                <a class="text-xs sm:text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors" href="{{ route('password.request') }}">
                  Lupa password?
                </a>
              @endif
            </div>
            <div class="flex items-center rounded-xl border border-gray-300 bg-white hover:border-blue-400 focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-100 transition-all duration-200">
              <div class="flex items-center justify-center px-3 sm:px-4 py-3 text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 1C8.676 1 6 3.676 6 7v2H4a1 1 0 00-1 1v12a1 1 0 001 1h16a1 1 0 001-1V10a1 1 0 00-1-1h-2V7c0-3.324-2.676-6-6-6zm0 2c2.276 0 4 1.724 4 4v2H8V7c0-2.276 1.724-4 4-4zm0 10a2 2 0 011 3.732V18a1 1 0 11-2 0v-1.268A2 2 0 0112 13z"/>
                </svg>
              </div>
              <input id="password" name="password" type="password" required
                     placeholder="••••••••"
                     class="flex-1 h-12 px-3 sm:px-4 outline-none bg-transparent text-gray-900 placeholder-gray-400 text-sm sm:text-base"/>
              <button type="button" id="togglePassword" class="flex items-center justify-center px-2 py-2 text-gray-500 hover:text-gray-700 transition-colors duration-200 focus:outline-none"
                aria-label="Toggle password visibility">
                <!-- Eye Icon for Password Toggle -->
                <svg id="passwordToggleIcon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <!-- Eye Closed (default) -->
                  <g id="eyeClosedState">
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                    <line x1="1" y1="1" x2="23" y2="23"></line>
                  </g>
                </svg>
                <!-- Eye Open (hidden by default) -->
                <svg id="passwordToggleIconOpen" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                  <circle cx="12" cy="12" r="3"></circle>
                </svg>
              </button>
            </div>
            @error('password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>

          {{-- Remember --}}
          <label class="flex items-center gap-3 text-sm text-gray-700 select-none cursor-pointer hover:text-gray-900">
            <input type="checkbox" name="remember" class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer transition-all duration-200">
            <span class="font-medium">Ingat saya</span>
          </label>

          {{-- Button --}}
          <button type="submit"
            class="w-full h-12 sm:h-13 rounded-xl bg-[#2E529F] text-white font-semibold shadow-md hover:shadow-lg hover:bg-[#23478d] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-[1.01] active:scale-[0.99] text-sm sm:text-base">
            Login ke Akun
          </button>
        
          {{-- Info pendaftaran ditutup publik --}}
          <div class="text-center pt-2">
            <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
              Untuk membuat akun baru, silakan hubungi administrator.
            </p>
          </div>
        </form>
      </div>

      {{-- Footer Info --}}
      <div class="text-center mt-6 sm:mt-8">
        <p class="text-xs sm:text-sm text-gray-500">
          Sistem Informasi Akademik Alwi College
        </p>
      </div>
    </div>
  </div>
</x-guest-layout>

<!-- Password Visibility Toggle Script -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const togglePasswordBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const passwordToggleIconClosed = document.getElementById('eyeClosedState');
    const passwordToggleIconOpen = document.getElementById('passwordToggleIconOpen');

    if (togglePasswordBtn && passwordInput && passwordToggleIconClosed && passwordToggleIconOpen) {
      togglePasswordBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Toggle input type
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';

        // Toggle icons
        passwordToggleIconClosed.classList.toggle('hidden');
        passwordToggleIconOpen.classList.toggle('hidden');
      });
    }

    // Add smooth animation to error messages
    const errorMessages = document.querySelectorAll('[class*="bg-red-50"]');
    errorMessages.forEach(msg => {
      msg.style.animation = 'slideIn 0.3s ease-out';
    });
  });
</script>

<style>
  @keyframes slideIn {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Smooth transitions for focus states */
  input[type="email"],
  input[type="password"] {
    transition: all 0.2s ease;
  }

  input[type="email"]:focus,
  input[type="password"]:focus {
    background-color: rgba(59, 130, 246, 0.02);
  }

  /* Mobile optimized button tapping */
  @media (hover: none) and (pointer: coarse) {
    button[type="submit"] {
      active: scale(0.98);
    }
  }

  /* Improved accessibility for focus-visible */
  input:focus-visible,
  button:focus-visible {
    outline: none;
  }
</style>

<!-- Improved WhatsApp FAB for Login: same behavior as welcome page -->
<div id="whatsapp-fab-login" class="fixed bottom-6 right-6 z-[9999]">
  <div class="group relative">
    <a href="https://wa.me/6282179970473" target="_blank" rel="noopener noreferrer"
       class="w-14 h-14 flex items-center justify-center bg-green-500 hover:bg-green-600 text-white rounded-full shadow-lg transition-transform duration-200 transform-gpu hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-300"
       aria-label="Hubungi Admin via WhatsApp">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.652a11.944 11.944 0 005.705 1.452h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>

    <div role="tooltip" class="absolute bottom-full mb-3 left-1/2 -translate-x-1/2 whitespace-nowrap bg-gray-900 text-white text-sm px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none shadow-lg">
      Hubungi Admin
    </div>
  </div>
</div>
