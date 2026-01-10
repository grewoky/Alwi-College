<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
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
        .btn-pop:hover{transform:translateY(-1px) scale(1.03)}
        /* Navbar scroll effect */
        .nav-scrolled{box-shadow:0 4px 12px rgba(0,0,0,0.10);filter:drop-shadow(0 6px 10px rgba(0,0,0,0.10));backdrop-filter:saturate(102%) blur(3px)}
        .nav-underline{position:absolute;left:0;right:0;bottom:-1px;height:8px;background:linear-gradient(to bottom, rgba(0,0,0,0.06), rgba(0,0,0,0));pointer-events:none}
        /* Reveal on scroll */
        .reveal{opacity:0;transform:translateY(12px);transition:opacity .5s ease, transform .5s ease}
        .reveal.show{opacity:1;transform:translateY(0)}
        /* Heading accent underline */
        .heading-accent{position:relative;display:block}
        .heading-inline{position:relative;display:inline-block}
        .heading-inline::after{content:"";position:absolute;left:0;bottom:-10px;width:100%;height:4px;border-radius:9999px;background:linear-gradient(90deg,#3B63B5 0%, #6FA2FF 50%, #3B63B5 100%);opacity:.25}
        .heading-accent-left{position:relative;display:block}
        .heading-accent-left .heading-inline::after{left:0}
        </style>
    </head>
    <body class="antialiased bg-white text-gray-900 overflow-x-hidden">
        <div class="min-h-screen flex flex-col">
            <!-- Navbar -->
            <nav id="main-nav" class="bg-[#2E529F]/85 backdrop-blur-sm border-b border-white/10 dark:bg-[#2E529F]/85 sticky top-0 z-50 shadow ring-1 ring-black/5 relative">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                        <div class="flex items-center">
                          <a href="{{ auth()->check() ? route('dashboard') : url('/') }}" class="flex-shrink-0 flex items-center gap-3">
                            <img src="{{ asset('images/logo.png') }}" class="h-8 w-auto" alt="Alwi College Logo">
                            <span class="text-xl font-semibold text-white dark:text-white">Alwi College</span>
                          </a>
                        </div>
                        <!-- Nav Links (Welcome only) -->
                        <div class="hidden md:flex items-center gap-2">
                    <a href="#top" class="px-3 py-1.5 rounded-md text-white/90 hover:text-white hover:bg-white/10 font-medium transition-colors nav-link focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/50" data-target="top">Beranda</a>
                    <a href="#features" class="px-3 py-1.5 rounded-md text-white/90 hover:text-white hover:bg-white/10 font-medium transition-colors nav-link focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/50" data-target="features">Program</a>
                    <a href="#alumni" class="px-3 py-1.5 rounded-md text-white/90 hover:text-white hover:bg-white/10 font-medium transition-colors nav-link focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/50" data-target="alumni">Alumni</a>
                    <a href="#about" class="px-3 py-1.5 rounded-md text-white/90 hover:text-white hover:bg-white/10 font-medium transition-colors nav-link focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/50" data-target="about">Tentang Kami</a>
                  </div>
                        <!-- Mobile menu button -->
                        <button id="mobile-menu-button" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/50" aria-label="Toggle navigation">
                          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>

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
                  <div class="nav-underline"></div>
            </nav>

            <!-- Mobile menu panel -->
            <div id="mobile-menu" class="md:hidden bg-[#2E529F]/95 backdrop-blur-sm text-white border-b border-white/10 hidden">
              <div class="max-w-7xl mx-auto px-4 py-3 space-y-2">
                <a href="#top" class="block py-2 px-2 rounded hover:bg-white/10 mobile-nav-link" data-target="top">Beranda</a>
                <a href="#features" class="block py-2 px-2 rounded hover:bg-white/10 mobile-nav-link" data-target="features">Program</a>
                <a href="#alumni" class="block py-2 px-2 rounded hover:bg-white/10 mobile-nav-link" data-target="alumni">Alumni</a>
                <a href="#about" class="block py-2 px-2 rounded hover:bg-white/10 mobile-nav-link" data-target="about">Tentang Kami</a>
                @if (Route::has('login'))
                  @auth
                    <a href="{{ route('dashboard') }}" class="block py-2 px-2 rounded bg-white text-[#2E529F] font-medium">Dashboard</a>
                  @else
                    <a href="{{ route('login') }}" class="block py-2 px-2 rounded bg-white text-[#2E529F] font-medium">Login</a>
                  @endauth
                @endif
              </div>
            </div>

            <script>
              (function(){
                const btn = document.getElementById('mobile-menu-button');
                const panel = document.getElementById('mobile-menu');
                if (btn && panel) {
                  btn.addEventListener('click', function(){
                    panel.classList.toggle('hidden');
                  });
                  panel.querySelectorAll('a').forEach(a => a.addEventListener('click', ()=> panel.classList.add('hidden')));
                }

                // Smooth scroll for internal anchors
                document.querySelectorAll('a[href^="#"]').forEach(link => {
                  link.addEventListener('click', function(e){
                    const targetId = this.getAttribute('href').slice(1);
                    const target = document.getElementById(targetId) || (targetId==='top' ? document.body : null);
                    if (target) {
                      e.preventDefault();
                      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                  });
                });

                // Active nav state by scroll position
                const navLinks = Array.from(document.querySelectorAll('.nav-link'));
                const mobileLinks = Array.from(document.querySelectorAll('#mobile-menu .mobile-nav-link'));
                const sections = {
                  top: document.body,
                  features: document.getElementById('features'),
                  alumni: document.getElementById('alumni'),
                  about: document.getElementById('about'),
                };

                function setActive(targetKey){
                  navLinks.forEach(a=>{
                    const isActive = a.dataset.target === targetKey;
                    a.classList.toggle('bg-white/15', isActive);
                    a.classList.toggle('text-white', isActive);
                    a.classList.toggle('text-white/90', !isActive);
                    a.setAttribute('aria-current', isActive ? 'page' : 'false');
                  });
                  mobileLinks.forEach(a=>{
                    const isActive = a.dataset.target === targetKey;
                    a.classList.toggle('bg-white/20', isActive);
                    a.classList.toggle('text-white', isActive);
                    a.classList.toggle('text-white/90', !isActive);
                    a.setAttribute('aria-current', isActive ? 'page' : 'false');
                  });
                }

                const io = new IntersectionObserver(entries => {
                  entries.forEach(entry => {
                    if (entry.isIntersecting){
                      const key = entry.target.id || 'top';
                      setActive(key);
                    }
                  });
                }, {rootMargin: '-40% 0px -50% 0px', threshold: 0.1});

                if (sections.features) io.observe(sections.features);
                if (sections.alumni) io.observe(sections.alumni);
                if (sections.about) io.observe(sections.about);
                setActive('top');

                // Navbar scroll shadow effect
                const nav = document.getElementById('main-nav');
                function onScroll(){
                  if (!nav) return;
                  const scrolled = window.scrollY > 4;
                  nav.classList.toggle('nav-scrolled', scrolled);
                }
                window.addEventListener('scroll', onScroll, {passive:true});
                onScroll();

                // Reveal elements on scroll
                const observer = new IntersectionObserver((entries)=>{
                  entries.forEach(entry=>{
                    if(entry.isIntersecting){
                      entry.target.classList.add('show');
                      observer.unobserve(entry.target);
                    }
                  });
                }, {threshold: 0.12});
                document.querySelectorAll('.reveal').forEach(el=> observer.observe(el));
              })();
            </script>

            <!-- Main content (will grow) -->
            <main class="flex-1">
              <!-- Hero Section -->
              <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @include('components.hero')
              </div>

      <!-- Features Section -->
      <div id="features" class="relative -mt-28 sm:-mt-16 md:-mt-20 lg:-mt-20 scroll-mt-24 sm:scroll-mt-28 bg-white">
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-0 sm:pt-4 pb-10">
          <h2 class="text-2xl sm:text-3xl font-semibold sm:font-bold text-center text-[#3B63B5] heading-accent"><span class="heading-inline">Mengapa Alwi College?</span></h2>
          <p class="text-center text-gray-600 text-base sm:text-lg mt-2 sm:mt-3 max-w-2xl mx-auto">Belajar jadi lebih fokus, menyenangkan, dan terarah dengan pendampingan intensif serta materi yang dirancang untuk hasil terbaik.</p>
          <div class="mt-5 sm:mt-6 grid gap-3 sm:gap-6 md:grid-cols-3">
            <article class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-100 card-hover reveal show text-center md:text-left">
              <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-500 text-white mb-2 sm:mb-4 mx-auto md:mx-0">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253"/></svg>
              </div>
              <h3 class="font-semibold text-base sm:text-lg mb-1 sm:mb-2">Materi Lengkap & Terstruktur</h3>
              <p class="text-sm sm:text-base text-gray-600">Kurikulum up-to-date, ringkasan materi, latihan terarah, dan bank soal berlevel.</p>
            </article>
            <article class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-100 card-hover reveal show text-center md:text-left">
              <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center bg-gradient-to-br from-emerald-500 to-teal-500 text-white mb-2 sm:mb-4 mx-auto md:mx-0">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M7 20H2v-2a4 4 0 014-4h1m10-5a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
              </div>
              <h3 class="font-semibold text-base sm:text-lg mb-1 sm:mb-2">Pengajar Berpengalaman</h3>
              <p class="text-sm sm:text-base text-gray-600">Mentor berpengalaman, pendampingan personal, dan strategi belajar yang efektif.</p>
            </article>
            <article class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-100 card-hover reveal show text-center md:text-left">
              <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center bg-gradient-to-br from-pink-500 to-rose-500 text-white mb-2 sm:mb-4 mx-auto md:mx-0">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9 9 0 1021 12h-8V4a9 9 0 00-2-.945z"/></svg>
              </div>
              <h3 class="font-semibold text-base sm:text-lg mb-1 sm:mb-2">Monitoring Progres</h3>
              <p class="text-sm sm:text-base text-gray-600">Pantau kemajuan belajar, nilai tugas, dan rekomendasi materi berikutnya.</p>
            </article>
          </div>
        </section>
      </div>

            <!-- Alumni Section -->
            <section id="alumni" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-6 sm:pb-10 scroll-mt-24 sm:scroll-mt-28">
              <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-md border border-gray-100 card-hover">
                <div class="fade-up">
                  <h2 class="text-2xl sm:text-3xl font-bold text-[#3B63B5] mb-2 sm:mb-3 heading-accent-left"><span class="heading-inline">Alumni Berprestasi</span></h2>
                  <p class="text-sm sm:text-base text-gray-600 leading-relaxed max-w-3xl">
                    Alwi College berkomitmen membangun kebiasaan belajar yang disiplin, terarah, dan konsisten—didukung mentor berpengalaman dan materi yang terstruktur.
                  </p>
                  <p class="text-sm sm:text-base text-gray-600 leading-relaxed max-w-3xl mt-2">
                    Berikut beberapa cerita singkat alumni yang terus berkembang dan berkontribusi di bidangnya.
                  </p>
                </div>

                <div class="mt-5 sm:mt-6 grid gap-3 sm:gap-6 md:grid-cols-3">
                  <article class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-100 card-hover reveal show">
                    <div class="flex flex-col sm:flex-row items-start gap-4">
                      <img src="{{ asset('images/posters/poster1-1600x1296.jpg.jpeg') }}" alt="Foto alumni Nadia Putri" class="w-20 h-20 sm:w-16 sm:h-16 rounded-2xl object-cover shrink-0 border border-gray-100">
                      <div class="min-w-0">
                        <p class="text-sm sm:text-base text-gray-600 leading-relaxed mb-4">
                          “Belajar di Alwi College membuat saya lebih disiplin dan percaya diri. Pola latihan yang terarah membantu saya membangun fondasi yang kuat.”
                        </p>
                        <div class="pt-4 border-t border-gray-100">
                          <p class="font-semibold text-gray-900">Nadia Putri</p>
                          <p class="text-xs sm:text-sm text-gray-600">Tahun lulus: 2020</p>
                          <p class="text-xs sm:text-sm text-gray-600">Profesi sekarang: Analis Data</p>
                        </div>
                      </div>
                    </div>
                  </article>

                  <article class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-100 card-hover reveal show">
                    <div class="flex flex-col sm:flex-row items-start gap-4">
                      <img src="{{ asset('images/posters/poster2-1600x1296.jpg.jpeg') }}" alt="Foto alumni Rizky Pratama" class="w-20 h-20 sm:w-16 sm:h-16 rounded-2xl object-cover shrink-0 border border-gray-100">
                      <div class="min-w-0">
                        <p class="text-sm sm:text-base text-gray-600 leading-relaxed mb-4">
                          “Pendampingan mentor dan pembahasan materi yang rapi membuat saya lebih mudah memahami konsep. Hasilnya terasa saat menghadapi ujian.”
                        </p>
                        <div class="pt-4 border-t border-gray-100">
                          <p class="font-semibold text-gray-900">Rizky Pratama</p>
                          <p class="text-xs sm:text-sm text-gray-600">Tahun lulus: 2019</p>
                          <p class="text-xs sm:text-sm text-gray-600">Profesi sekarang: Guru Matematika</p>
                        </div>
                      </div>
                    </div>
                  </article>

                  <article class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-100 card-hover reveal show">
                    <div class="flex flex-col sm:flex-row items-start gap-4">
                      <img src="{{ asset('images/posters/poster3-1600x1296.jpg.jpeg') }}" alt="Foto alumni Siti Aisyah" class="w-20 h-20 sm:w-16 sm:h-16 rounded-2xl object-cover shrink-0 border border-gray-100">
                      <div class="min-w-0">
                        <p class="text-sm sm:text-base text-gray-600 leading-relaxed mb-4">
                          “Lingkungan belajarnya suportif dan latihan soalnya bertahap. Saya jadi terbiasa menyusun strategi belajar dan mengelola waktu.”
                        </p>
                        <div class="pt-4 border-t border-gray-100">
                          <p class="font-semibold text-gray-900">Siti Aisyah</p>
                          <p class="text-xs sm:text-sm text-gray-600">Tahun lulus: 2021</p>
                          <p class="text-xs sm:text-sm text-gray-600">Profesi sekarang: Mahasiswa Kedokteran</p>
                        </div>
                      </div>
                    </div>
                  </article>
                </div>
              </div>
            </section>

            <!-- About Section -->
            <section id="about" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 scroll-mt-24 sm:scroll-mt-28">
              <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-md border border-gray-100 card-hover">
                <div class="grid md:grid-cols-2 gap-6 sm:gap-8 items-center">
            <!-- Gambar About Alwi -->
            <div class="flex justify-center md:justify-start fade-up">
              <div class="relative w-full max-w-xs sm:max-w-sm md:max-w-md">
                  <div class="aspect-square rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-shadow duration-300">
                  <img src="{{ asset('images/About_Alwi.png') }}" alt="About Alwi College" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500 max-w-full">
                </div>
              </div>
            </div>

                <!-- Teks Tentang Kami -->
                <div class="fade-up delay-200">
                  <h2 class="text-2xl sm:text-3xl font-bold text-[#3B63B5] mb-3 sm:mb-4 heading-accent-left"><span class="heading-inline">Tentang Kami</span></h2>
                  <p class="text-sm sm:text-base text-gray-600 leading-relaxed mb-3 sm:mb-4">
                Alwi College didirikan pada tahun 2003, berawal dari sebuah visi sederhana untuk memberikan pendidikan berkualitas tinggi kepada masyarakat. Dengan komitmen terhadap keunggulan akademik dan pengembangan karakter, kami terus berinovasi dalam metode pembelajaran.
              </p>
                  <p class="text-sm sm:text-base text-gray-600 leading-relaxed mb-5 sm:mb-6">
                Kami percaya bahwa setiap siswa memiliki potensi unik untuk berkembang. Oleh karena itu, Alwi College menyediakan lingkungan belajar yang mendukung, tim pengajar berpengalaman, dan fasilitas modern untuk mendukung perjalanan akademik Anda menuju kesuksesan.
              </p>
                  <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                    <span class="px-2.5 sm:px-3 py-1 rounded-full text-xs sm:text-sm bg-emerald-50 text-emerald-700 border border-emerald-100">Kelas aktif & Berkompeten</span>
                    <span class="px-2.5 sm:px-3 py-1 rounded-full text-xs sm:text-sm bg-indigo-50 text-indigo-700 border border-indigo-100">Materi Terstruktur</span>
                    <span class="px-2.5 sm:px-3 py-1 rounded-full text-xs sm:text-sm bg-pink-50 text-pink-700 border border-pink-100">Pendampingan Intensif</span>
                  </div>
                </div>
              </div>

              <!-- CTA Section -->
              <div class="mt-8 sm:mt-10">
                <div class="bg-[#3B63B5] rounded-2xl p-5 sm:p-8 md:p-10 text-center text-white relative overflow-hidden">
                  <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                  <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                  <h3 class="text-xl sm:text-2xl font-semibold">Siap Tingkatkan Prestasi Belajar?</h3>
                  <p class="opacity-90 mt-2 text-sm sm:text-base">Gabung sekarang dan rasakan pengalaman belajar yang efektif, seru, dan terukur.</p>
                  <div class="mt-4 sm:mt-5 flex items-center justify-center gap-3">
                    <a href="{{ route('login') }}" class="px-4 sm:px-5 py-2 sm:py-2.5 rounded-lg bg-white text-[#2E529F] font-medium shadow-sm hover:shadow btn-glow btn-pop text-sm sm:text-base">Mulai Sekarang</a>
                  </div>
                </div>
              </div>
            </section>

            </main>

            <!-- Footer (enhanced) -->
            <footer class="bg-[#3B63B5] mt-10 sm:mt-12 text-white w-full left-0 right-0">
              <div class="w-full">
                <div class="max-w-7xl mx-auto py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
                  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                    <!-- Brand / About -->
                    <div>
                      <div class="flex items-center gap-2 sm:gap-3 mb-2 sm:mb-3">
                        <img src="{{ asset('images/logo.png') }}" class="h-7 sm:h-8 w-auto" alt="Alwi College Logo">
                        <span class="text-lg sm:text-xl font-semibold">Alwi College</span>
                      </div>
                      <p class="text-white/80 text-xs sm:text-sm leading-relaxed">
                        Bimbel fokus, menyenangkan, dan terarah dengan pendampingan intensif serta materi terstruktur untuk hasil terbaik.
                      </p>
                    </div>

                    <!-- Quick Links -->
                    <div>
                      <h4 class="text-base sm:text-lg font-semibold mb-2 sm:mb-3">Info Lebih Lanjut</h4>
                      <ul class="space-y-1.5 sm:space-y-2 text-white/90 text-xs sm:text-sm">
                        <li><a href="#about" class="hover:underline">Tentang Kami</a></li>
                        <li><a href="#features" class="hover:underline">Program & Fitur</a></li>
                        @if (Route::has('login'))
                          <li><a href="{{ route('login') }}" class="hover:underline">Masuk</a></li>
                        @endif
                      </ul>
                    </div>

                    <!-- Contact -->
                    <div>
                      <h4 class="text-base sm:text-lg font-semibold mb-2 sm:mb-3">Hubungi Kami</h4>
                      <ul class="space-y-1.5 sm:space-y-2 text-white/90 text-xs sm:text-sm">
                        <li class="flex items-center gap-2">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2 8l10-6 10 6-10 6L2 8zm0 8l10 6 10-6"/></svg>
                          <span>Jl. Kebun Mnaggis Gg Salam 619CD</span>
                        </li>
                        <li class="flex items-center gap-2">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5h18M3 19h18M3 12h18"/></svg>
                          <span>Email: info@alwicollege.id</span>
                        </li>
                        <li class="flex items-center gap-2">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5h4l2 7-2 7H3m7-14h11l-2 7 2 7H10"/></svg>
                          <span>Telepon/WA: 0899-4432-225</span>
                        </li>
                      </ul>
                    </div>

                    <!-- Socials -->
                    <div>
                      <h4 class="text-base sm:text-lg font-semibold mb-2 sm:mb-3">Ikuti Kami</h4>
                      <div class="flex items-center gap-3">
                        <!-- Instagram -->
                        <a href="https://www.instagram.com/bimbel_alwi_college/" target="_blank" rel="noopener noreferrer" aria-label="Instagram Alwi College" class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center">
                          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M7 2C4.243 2 2 4.243 2 7v10c0 2.757 2.243 5 5 5h10c2.757 0 5-2.243 5-5V7c0-2.757-2.243-5-5-5H7zm0 2h10c1.654 0 3 1.346 3 3v10c0 1.654-1.346 3-3 3H7c-1.654 0-3-1.346-3-3V7c0-1.654 1.346-3 3-3zm11 1a1 1 0 110 2 1 1 0 010-2zM12 7a5 5 0 100 10 5 5 0 000-10z"/></svg>
                        </a>
                        <!-- WhatsApp -->
                        <a href="https://wa.me/628994432225" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp Admin" class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center">
                          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884"/></svg>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Bottom bar copyright -->
                <div class="bg-black/10">
                  <div class="max-w-7xl mx-auto py-3 sm:py-4 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-center">
                      <p class="text-xs sm:text-sm text-white/90 text-center">© {{ date('Y') }} Alwi College. All Rights Reserved.</p>
                    </div>
                  </div>
                </div>
              </div>
            </footer>

            <!-- WhatsApp FAB: dipindahkan ke akhir body agar selalu clickable dan tidak tertutup -->
        </div>
    
        <!-- Improved WhatsApp FAB: selalu terlihat dan clickable, tooltip 'Hubungi Admin' -->
        <div id="whatsapp-fab" class="fixed bottom-6 right-6 z-[9999]">
          <div class="group relative">
            <a href="https://wa.me/628994432225" target="_blank" rel="noopener noreferrer"
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
    </body>
</html>