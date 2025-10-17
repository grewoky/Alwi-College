<x-app-layout>
  <x-slot name="title">Siswa • Dashboard</x-slot>

  <div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- CAROUSEL BANNER -->
      <div class="mb-8">
        <div class="relative w-full bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl overflow-hidden shadow-lg">
          <div class="carousel-container relative w-full h-80 md:h-96 bg-gray-900 rounded-2xl overflow-hidden">
            <!-- Slide 1 -->
            <div class="carousel-slide absolute w-full h-full transition-opacity duration-1000 opacity-100" data-index="0">
              <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-600 to-indigo-700">
                <div class="text-center text-white px-8">
                  <h3 class="text-3xl md:text-4xl font-bold mb-4">Selamat Datang di Alwi College</h3>
                  <p class="text-lg md:text-xl">Membangun Masa Depan Cerah Melalui Pendidikan Berkualitas</p>
                </div>
              </div>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-slide absolute w-full h-full transition-opacity duration-1000 opacity-0" data-index="1">
              <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-green-600 to-teal-700">
                <div class="text-center text-white px-8">
                  <h3 class="text-3xl md:text-4xl font-bold mb-4">Raih Prestasi Terbaik</h3>
                  <p class="text-lg md:text-xl">Belajar dengan Teknologi dan Metode Pembelajaran Modern</p>
                </div>
              </div>
            </div>

            <!-- Slide 3 -->
            <div class="carousel-slide absolute w-full h-full transition-opacity duration-1000 opacity-0" data-index="2">
              <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-600 to-pink-700">
                <div class="text-center text-white px-8">
                  <h3 class="text-3xl md:text-4xl font-bold mb-4">Bergabunglah dengan Komunitas</h3>
                  <p class="text-lg md:text-xl">Mari Tumbuh Bersama dalam Lingkungan Belajar yang Positif</p>
                </div>
              </div>
            </div>

            <!-- Navigation Dots -->
            <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex gap-2 z-10">
              <button class="carousel-dot w-3 h-3 rounded-full bg-white opacity-100 cursor-pointer transition-opacity" data-index="0"></button>
              <button class="carousel-dot w-3 h-3 rounded-full bg-white opacity-50 cursor-pointer transition-opacity hover:opacity-75" data-index="1"></button>
              <button class="carousel-dot w-3 h-3 rounded-full bg-white opacity-50 cursor-pointer transition-opacity hover:opacity-75" data-index="2"></button>
            </div>

            <!-- Navigation Arrows -->
            <button class="carousel-prev absolute left-4 top-1/2 transform -translate-y-1/2 z-10 bg-white/30 hover:bg-white/50 text-white rounded-full p-2 transition">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
              </svg>
            </button>
            <button class="carousel-next absolute right-4 top-1/2 transform -translate-y-1/2 z-10 bg-white/30 hover:bg-white/50 text-white rounded-full p-2 transition">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- NOTIFIKASI PEMBAYARAN -->
      <div class="mb-8" id="paymentNotification">
        @php
          $currentMonth = now()->format('m');
          $currentYear = now()->format('Y');
          $monthPeriod = $currentMonth . '-' . $currentYear;
          
          $paymentThisMonth = $payments->where('month_period', $monthPeriod)
                                        ->where('status', 'approved')
                                        ->first();
        @endphp

        @if(!$paymentThisMonth)
          <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
            <div class="flex items-start">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
              </div>
              <div class="ml-3 flex-1">
                <h3 class="text-sm font-semibold text-red-800">Pembayaran Belum Lunas</h3>
                <p class="mt-1 text-sm text-red-700">Anda belum melakukan pembayaran untuk bulan {{ now()->format('F Y') }}. Silakan segera upload bukti pembayaran Anda.</p>
                <div class="mt-3">
                  <a href="{{ route('pay.index') }}" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition">
                    Upload Bukti Pembayaran
                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                  </a>
                </div>
              </div>
            </div>
          </div>
        @else
          <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
            <div class="flex items-start">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
              </div>
              <div class="ml-3">
                <h3 class="text-sm font-semibold text-green-800">Pembayaran Bulan Ini Sudah Lunas</h3>
                <p class="mt-1 text-sm text-green-700">Terima kasih telah melunasi pembayaran untuk bulan {{ now()->format('F Y') }}.</p>
              </div>
            </div>
          </div>
        @endif
      </div>

      <!-- INFO BIMBINGAN CARD -->
      <div class="mb-8">
        <div class="grid md:grid-cols-3 gap-6">
          <!-- Card 1 -->
          <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition border border-gray-100">
            <div class="flex items-center mb-4">
              <div class="flex-shrink-0">
                <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-blue-100">
                  <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.998 10-10.747S17.5 6.253 12 6.253z"></path>
                  </svg>
                </div>
              </div>
              <h3 class="ml-3 text-lg font-semibold text-gray-900">Bimbingan Akademik</h3>
            </div>
            <p class="text-gray-600 text-sm leading-relaxed">Dapatkan dukungan penuh dari tim pengajar berpengalaman kami untuk menghadapi tantangan akademik dan meraih prestasi terbaik.</p>
            <div class="mt-4">
              <a href="#" class="text-blue-600 hover:text-blue-700 font-medium text-sm inline-flex items-center">
                Pelajari Lebih Lanjut
                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
              </a>
            </div>
          </div>

          <!-- Card 2 -->
          <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition border border-gray-100">
            <div class="flex items-center mb-4">
              <div class="flex-shrink-0">
                <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-green-100">
                  <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                  </svg>
                </div>
              </div>
              <h3 class="ml-3 text-lg font-semibold text-gray-900">Monitoring Nilai</h3>
            </div>
            <p class="text-gray-600 text-sm leading-relaxed">Pantau perkembangan nilai akademik Anda secara real-time dan identifikasi area yang perlu ditingkatkan untuk sukses.</p>
            <div class="mt-4">
              <a href="#" class="text-green-600 hover:text-green-700 font-medium text-sm inline-flex items-center">
                Lihat Nilai Saya
                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
              </a>
            </div>
          </div>

          <!-- Card 3 -->
          <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition border border-gray-100">
            <div class="flex items-center mb-4">
              <div class="flex-shrink-0">
                <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-purple-100">
                  <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
              </div>
              <h3 class="ml-3 text-lg font-semibold text-gray-900">Beasiswa & Program</h3>
            </div>
            <p class="text-gray-600 text-sm leading-relaxed">Jangan lewatkan berbagai program beasiswa dan bantuan keuangan yang tersedia untuk mendukung pendidikan Anda.</p>
            <div class="mt-4">
              <a href="#" class="text-purple-600 hover:text-purple-700 font-medium text-sm inline-flex items-center">
                Cek Ketersediaan
                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- TENTANG KAMI SECTION -->
      <div class="mb-8">
        <div class="bg-white rounded-2xl p-8 shadow-md border border-gray-100">
          <div class="grid md:grid-cols-2 gap-8 items-center">
            <!-- Gambar Placeholder -->
            <div class="flex justify-center md:justify-start">
              <div class="relative w-full max-w-md">
                <div class="aspect-square bg-gradient-to-br from-gray-200 to-gray-300 rounded-2xl overflow-hidden shadow-lg flex items-center justify-center">
                  <div class="text-center">
                    <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-gray-500 font-medium">Gambar Sekolah</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Teks Tentang Kami -->
            <div>
              <h2 class="text-3xl font-bold text-gray-900 mb-4">Tentang Kami</h2>
              <p class="text-gray-600 leading-relaxed mb-4">
                Alwi College didirikan pada tahun 2023, berawal dari sebuah visi sederhana untuk memberikan pendidikan berkualitas tinggi kepada masyarakat. Dengan komitmen terhadap keunggulan akademik dan pengembangan karakter, kami terus berinovasi dalam metode pembelajaran.
              </p>
              <p class="text-gray-600 leading-relaxed mb-6">
                Kami percaya bahwa setiap siswa memiliki potensi unik untuk berkembang. Oleh karena itu, Alwi College menyediakan lingkungan belajar yang mendukung, tim pengajar berpengalaman, dan fasilitas modern untuk mendukung perjalanan akademik Anda menuju kesuksesan.
              </p>
              
              <!-- Statistik -->
              <div class="grid grid-cols-3 gap-4 pt-6 border-t border-gray-200">
                <div class="text-center">
                  <p class="text-2xl font-bold text-blue-600">{{ $totalStudents ?? 0 }}</p>
                  <p class="text-sm text-gray-600">Siswa Aktif</p>
                </div>
                <div class="text-center">
                  <p class="text-2xl font-bold text-green-600">{{ $totalTeachers ?? 0 }}</p>
                  <p class="text-sm text-gray-600">Pengajar Berpengalaman</p>
                </div>
                <div class="text-center">
                  <p class="text-2xl font-bold text-purple-600">2023</p>
                  <p class="text-sm text-gray-600">Berdiri Sejak</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- QUICK ACCESS CARDS -->
      <div class="grid md:grid-cols-2 gap-6">
        <a href="{{ route('info.index') }}" class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 shadow-md hover:shadow-lg transition text-white group">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-lg font-semibold mb-2">Unggah Info / Kisi-kisi</h3>
              <p class="text-blue-100 text-sm">Bagikan materi pembelajaran dengan pengajar</p>
            </div>
            <svg class="w-12 h-12 text-blue-400 group-hover:translate-x-2 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
          </div>
        </a>

        <a href="{{ route('pay.index') }}" class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 shadow-md hover:shadow-lg transition text-white group">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-lg font-semibold mb-2">Riwayat Pembayaran</h3>
              <p class="text-green-100 text-sm">Lihat semua transaksi pembayaran Anda</p>
            </div>
            <svg class="w-12 h-12 text-green-400 group-hover:translate-x-2 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
        </a>
      </div>

    </div>
  </div>

  <!-- Carousel Script -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      let currentSlide = 0;
      const slides = document.querySelectorAll('.carousel-slide');
      const dots = document.querySelectorAll('.carousel-dot');
      const totalSlides = slides.length;

      function showSlide(n) {
        slides.forEach((slide, index) => {
          slide.classList.toggle('opacity-100', index === n);
          slide.classList.toggle('opacity-0', index !== n);
        });
        dots.forEach((dot, index) => {
          dot.classList.toggle('opacity-100', index === n);
          dot.classList.toggle('opacity-50', index !== n);
        });
      }

      function nextSlide() {
        currentSlide = (currentSlide + 1) % totalSlides;
        showSlide(currentSlide);
      }

      function prevSlide() {
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        showSlide(currentSlide);
      }

      // Auto-advance carousel every 5 seconds
      let carouselInterval = setInterval(nextSlide, 5000);

      // Navigation buttons
      document.querySelector('.carousel-next').addEventListener('click', function() {
        clearInterval(carouselInterval);
        nextSlide();
        carouselInterval = setInterval(nextSlide, 5000);
      });

      document.querySelector('.carousel-prev').addEventListener('click', function() {
        clearInterval(carouselInterval);
        prevSlide();
        carouselInterval = setInterval(nextSlide, 5000);
      });

      // Dot navigation
      dots.forEach((dot, index) => {
        dot.addEventListener('click', function() {
          clearInterval(carouselInterval);
          currentSlide = index;
          showSlide(currentSlide);
          carouselInterval = setInterval(nextSlide, 5000);
        });
      });

      // Pause on hover
      document.querySelector('.carousel-container').addEventListener('mouseenter', function() {
        clearInterval(carouselInterval);
      });

      document.querySelector('.carousel-container').addEventListener('mouseleave', function() {
        carouselInterval = setInterval(nextSlide, 5000);
      });
    });
  </script>
</x-app-layout>
