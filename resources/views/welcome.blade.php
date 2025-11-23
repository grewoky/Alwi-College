<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Alwi College - Lembaga Pendidikan Terpercaya">

        <title>{{ config('app.name', 'Alwi College') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&family=poppins:400,500,600,700" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/main.jsx'])

        <style>
        @keyframes fadeInUp{from{opacity:0;transform:translateY(30px)}to{opacity:1;transform:translateY(0)}}
        @keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-15px)}}
        .fade-up{animation:fadeInUp 0.8s ease-out forwards}
        .float-anim{animation:float 3s ease-in-out infinite}
        .card-hover{transition:all 0.3s cubic-bezier(0.4,0,0.2,1)}
        .card-hover:hover{transform:translateY(-10px) scale(1.02);box-shadow:0 20px 40px rgba(0,0,0,0.15)}
        .hero-gradient{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%)}
        .btn-glow:hover{box-shadow:0 0 20px rgba(102,126,234,0.6)}
        </style>
    </head>
    <body class="antialiased bg-white text-gray-900">
        <div class="min-h-screen">
            <!-- Navbar -->
            <nav class="bg-[#2E529F] border-b border-gray-200 dark:bg-[#2E529F] sticky top-0 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 flex items-center gap-3">
                                <img src="{{ asset('images/logo.png') }}" class="h-8 w-auto" alt="Alwi College Logo">
                                <span class="text-xl font-semibold text-white dark:text-white">Alwi College</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-5 py-2 border border-white text-sm font-medium rounded-lg text-[#2E529F] bg-white hover:bg-gray-50 focus:ring-4 focus:ring-white/50 transition-all duration-150 ease-in-out">
                                        Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="inline-flex items-center px-5 py-2 border border-white text-sm font-medium rounded-lg text-[#2E529F] bg-white hover:bg-gray-50 focus:ring-4 focus:ring-white/50 transition-all duration-150 ease-in-out">
                                        Login
                                    </a>
                                @endauth
                            @endif
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Hero Section (React mount) -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div id="welcome-hero" data-props='{}'>
                  <div class="relative w-full rounded-2xl overflow-hidden shadow-lg">
                    <div class="carousel-container relative w-full h-80 md:h-96 rounded-2xl overflow-hidden bg-gray-900">
                      {{-- Slide 1 --}}
                      <div class="carousel-slide absolute inset-0 transition-opacity duration-1000 opacity-100 fade-up" data-index="0">
                        <div class="w-full h-full flex items-center justify-center hero-gradient">
                          <div class="text-center text-white px-8 fade-up delay-100">
                            <h3 class="text-3xl md:text-4xl font-bold mb-4 float-anim">Selamat Datang di Alwi College</h3>
                            <p class="text-lg md:text-xl fade-up delay-200">Membangun Masa Depan Cerah Melalui Pendidikan Berkualitas</p>
                          </div>
                        </div>
                      </div>

                      {{-- Slide 2 --}}
                      <div class="carousel-slide absolute inset-0 transition-opacity duration-1000 opacity-0" data-index="1">
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-green-600 to-teal-700">
                          <div class="text-center text-white px-8">
                            <h3 class="text-3xl md:text-4xl font-bold mb-4 float-anim"> Raih Prestasi Terbaik</h3>
                            <p class="text-lg md:text-xl">Belajar dengan Teknologi dan Metode Pembelajaran Modern</p>
                          </div>
                        </div>
                      </div>

                      {{-- Slide 3 --}}
                      <div class="carousel-slide absolute inset-0 transition-opacity duration-1000 opacity-0" data-index="2">
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-600 to-pink-700">
                          <div class="text-center text-white px-8">
                            <h3 class="text-3xl md:text-4xl font-bold mb-4 float-anim"> Bergabunglah dengan Komunitas</h3>
                            <p class="text-lg md:text-xl">Mari tumbuh bersama dalam lingkungan belajar yang positif</p>
                          </div>
                        </div>
                      </div>

                      {{-- Dots --}}
                      <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                        <button class="carousel-dot w-3 h-3 rounded-full bg-white opacity-100" data-index="0"></button>
                        <button class="carousel-dot w-3 h-3 rounded-full bg-white opacity-50" data-index="1"></button>
                        <button class="carousel-dot w-3 h-3 rounded-full bg-white opacity-50" data-index="2"></button>
                      </div>

                      {{-- Arrows --}}
                      <button class="carousel-prev absolute left-4 top-1/2 -translate-y-1/2 z-10 bg-white/30 hover:bg-white/50 text-white rounded-full p-2 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                      </button>
                      <button class="carousel-next absolute right-4 top-1/2 -translate-y-1/2 z-10 bg-white/30 hover:bg-white/50 text-white rounded-full p-2 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
            </div>

      <!-- Features Section -->
      <section id="features" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h2 class="text-3xl font-bold text-center bg-gradient-to-r from-purple-600 to-blue-500 bg-clip-text text-transparent">Mengapa Alwi College?</h2>
        <p class="text-center text-gray-600 mt-3 max-w-2xl mx-auto">Belajar jadi lebih fokus, menyenangkan, dan terarah dengan pendampingan intensif serta materi yang dirancang untuk hasil terbaik.</p>
        <div class="grid md:grid-cols-3 gap-6 mt-8">
          <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 card-hover fade-up">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-500 text-white mb-4">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253"/></svg>
            </div>
            <h3 class="font-semibold text-lg mb-2">Materi Lengkap & Terstruktur</h3>
            <p class="text-gray-600">Kurikulum up-to-date, ringkasan materi, latihan terarah, dan bank soal berlevel.</p>
          </div>
          <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 card-hover fade-up delay-100">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-gradient-to-br from-emerald-500 to-teal-500 text-white mb-4">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M7 20H2v-2a4 4 0 014-4h1m10-5a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <h3 class="font-semibold text-lg mb-2">Pengajar Berpengalaman</h3>
            <p class="text-gray-600">Mentor berpengalaman, pendampingan personal, dan strategi belajar efektif.</p>
          </div>
          <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 card-hover fade-up delay-200">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-gradient-to-br from-pink-500 to-rose-500 text-white mb-4">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9 9 0 1021 12h-8V4a9 9 0 00-2-.945z"/></svg>
            </div>
            <h3 class="font-semibold text-lg mb-2">Monitoring Progres</h3>
            <p class="text-gray-600">Pantau kemajuan belajar, nilai tugas, dan rekomendasi materi berikutnya.</p>
          </div>
        </div>

      </section>

            <!-- About Section -->
            <section id="about" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
               <div class="bg-white rounded-2xl p-8 shadow-md border border-gray-100 card-hover">
          <div class="grid md:grid-cols-2 gap-8 items-center">
            <!-- Gambar About Alwi -->
            <div class="flex justify-center md:justify-start fade-up">
              <div class="relative w-full max-w-md">
                <div class="aspect-square rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-shadow duration-300">
                  <img src="{{ asset('images/About_Alwi.png') }}" alt="About Alwi College" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                </div>
              </div>
            </div>

            <!-- Teks Tentang Kami -->
            <div class="fade-up delay-200">
              <h2 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-blue-500 bg-clip-text text-transparent mb-4">Tentang Kami</h2>
              <p class="text-gray-600 leading-relaxed mb-4">
                Alwi College didirikan pada tahun 2003, berawal dari sebuah visi sederhana untuk memberikan pendidikan berkualitas tinggi kepada masyarakat. Dengan komitmen terhadap keunggulan akademik dan pengembangan karakter, kami terus berinovasi dalam metode pembelajaran.
              </p>
              <p class="text-gray-600 leading-relaxed mb-6">
                Kami percaya bahwa setiap siswa memiliki potensi unik untuk berkembang. Oleh karena itu, Alwi College menyediakan lingkungan belajar yang mendukung, tim pengajar berpengalaman, dan fasilitas modern untuk mendukung perjalanan akademik Anda menuju kesuksesan.
              </p>
              <div class="flex flex-wrap items-center gap-3">
                <span class="px-3 py-1 rounded-full text-sm bg-emerald-50 text-emerald-700 border border-emerald-100">Kelas Kecil & Fokus</span>
                <span class="px-3 py-1 rounded-full text-sm bg-indigo-50 text-indigo-700 border border-indigo-100">Materi Terstruktur</span>
                <span class="px-3 py-1 rounded-full text-sm bg-pink-50 text-pink-700 border border-pink-100">Pendampingan Intensif</span>
              </div>
    </div>
  </div>

          <!-- CTA Section -->
          <div class="mt-10">
            <div class="hero-gradient rounded-2xl p-8 md:p-10 text-center text-white relative overflow-hidden">
              <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
              <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
              <h3 class="text-2xl font-semibold">Siap Tingkatkan Prestasi Belajar?</h3>
              <p class="opacity-90 mt-2">Gabung sekarang dan rasakan pengalaman belajar yang efektif, seru, dan terukur.</p>
              <div class="mt-5 flex items-center justify-center gap-3">
                <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-lg bg-white text-[#2E529F] font-medium shadow-sm hover:shadow btn-glow">Mulai Sekarang</a>
                <a href="#features" class="px-5 py-2.5 rounded-lg border border-white/70 text-white font-medium hover:bg-white/10">Lihat Fitur</a>
              </div>
            </div>
          </div>

            <!-- Footer -->
            <footer class="hero-gradient mt-12 text-white">
                <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <p class="text-lg font-semibold mb-2">Alwi College</p>
                        <p class="text-sm opacity-90">© {{ date('Y') }} All rights reserved.</p>
                    </div>
                </div>
            </footer>

            <div id="whatsapp-fab">
              <!-- static fallback WhatsApp button (will be replaced by React if it mounts) -->
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
                  Hubungi Customer Service
                </span>
              </div>
            </div>
        </div>
    </body>
</html>