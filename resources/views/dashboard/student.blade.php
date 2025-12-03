<x-app-layout>
  <x-slot name="title">Siswa • Dashboard</x-slot>

  <div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <style>
        .card-hover{transition:all .25s cubic-bezier(0.4,0,0.2,1)}
        .card-hover:hover{transform:translateY(-4px);box-shadow:0 12px 24px rgba(0,0,0,0.08)}
        .heading-inline{position:relative;display:inline-block}
        .heading-inline::after{content:"";position:absolute;left:0;bottom:-10px;width:100%;height:4px;border-radius:9999px;background:linear-gradient(90deg,#3B63B5 0%, #6FA2FF 50%, #3B63B5 100%);opacity:.25}
      </style>
      
      <!-- CAROUSEL BANNER -->
      <div class="mb-8">
        <div class="relative w-full bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl overflow-hidden shadow-lg">
          <div class="carousel-container relative w-full h-64 md:h-80 lg:h-96 bg-gray-900 rounded-2xl overflow-hidden">
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
          // Payments store `month_period` using format `Y-m` (e.g. 2025-11).
          // Use the same format here so we correctly detect approved payments for current month.
          $monthPeriod = now()->format('Y-m');

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

      <!-- Quick Access Cards -->
      <div class="mb-10">
        <h2 class="text-2xl font-bold text-gray-900 mb-6"><span class="heading-inline">Akses Cepat</span></h2>

        @php
          $studentLinks = [
            [
              'label' => 'Jadwal Pelajaran',
              'description' => 'Lihat seluruh jadwal dari semua sekolah',
              'route' => route('lessons.student')
            ],
            [
              'label' => 'Pembayaran',
              'description' => 'Upload bukti dan cek status pembayaran bulanan',
              'route' => route('pay.index')
            ],
            [
              'label' => 'Info Materi',
              'description' => 'Unggah atau unduh kisi-kisi dan materi penting',
              'route' => route('info.index')
            ],
            [
              'label' => 'Riwayat Kehadiran',
              'description' => 'Pantau catatan absensi pribadi setiap jadwal',
              'route' => route('attendance.student')
            ],
          ];
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
          @foreach ($studentLinks as $link)
            <a href="{{ $link['route'] }}" class="group bg-white border border-slate-200 rounded-xl p-6 shadow-sm hover:shadow-md transition flex flex-col card-hover focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-300">
              <div class="flex items-center justify-between mb-2">
                <h3 class="text-base font-semibold text-slate-900">{{ $link['label'] }}</h3>
                <svg class="w-5 h-5 text-slate-300 group-hover:text-indigo-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 5l7 7-7 7" />
                </svg>
              </div>
              <p class="text-sm text-slate-500 flex-grow">{{ $link['description'] }}</p>
              <span class="mt-4 text-sm font-medium text-indigo-600">Masuk Sekarang</span>
            </a>
          @endforeach
        </div>
      </div>

      <!-- TENTANG KAMI SECTION -->
      <div class="mb-8">
        <div class="bg-white rounded-2xl p-8 shadow-md border border-gray-100">
          <div class="grid md:grid-cols-2 gap-8 items-center">
            <!-- Gambar About Alwi -->
            <div class="flex justify-center md:justify-start">
              <div class="relative w-full max-w-md">
                <div class="aspect-square rounded-2xl overflow-hidden shadow-lg">
                  <img src="{{ asset('images/About_Alwi.png') }}" alt="About Alwi College" class="w-full h-full object-cover">
                </div>
              </div>
            </div>

            <!-- Teks Tentang Kami -->
            <div>
              <h2 class="text-3xl font-bold text-gray-900 mb-4"><span class="heading-inline">Tentang Kami</span></h2>
              <p class="text-gray-600 leading-relaxed mb-4">
                Alwi College didirikan pada tahun 2003, berawal dari sebuah visi sederhana untuk memberikan pendidikan berkualitas tinggi kepada masyarakat. Dengan komitmen terhadap keunggulan akademik dan pengembangan karakter, kami terus berinovasi dalam metode pembelajaran.
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
                  <p class="text-2xl font-bold text-purple-600">2003</p>
                  <p class="text-sm text-gray-600">Berdiri Sejak</p>
                </div>
              </div>
            </div>
          </div>
        </div>
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
