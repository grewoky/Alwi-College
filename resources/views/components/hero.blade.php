<section class="relative bg-white w-full pt-4 sm:pt-5 md:pt-8 lg:pt-10">
    <!-- Carousel Container - full width without extra padding on mobile -->
    <div class="w-full px-0 md:px-6 lg:px-8">
      @php
        // If the controller hasn't provided $posters, build it from files in public/images/posters
        if (!isset($posters) || empty($posters)) {
          $posters = [];
          try {
            $files = \Illuminate\Support\Facades\File::glob(public_path('images/posters/*.{jpg,jpeg,png,webp,gif,svg}'), GLOB_BRACE) ?: [];
            foreach ($files as $filePath) {
              $posters[] = asset('images/posters/' . basename($filePath));
            }
            // Prioritize a specific poster as the first slide if it exists
            $priorityName = 'Alwi_CollegeDisc.png';
            $priorityIndex = null;
            foreach ($posters as $idx => $url) {
              $name = basename(parse_url($url, PHP_URL_PATH));
              if ($name === $priorityName) { $priorityIndex = $idx; break; }
            }
            if ($priorityIndex !== null) {
              $priorityUrl = $posters[$priorityIndex];
              unset($posters[$priorityIndex]);
              array_unshift($posters, $priorityUrl);
              $posters = array_values($posters);
            }
          } catch (\Throwable $e) {
            $posters = [];
          }
        }
      @endphp

      <!-- MOBILE CAROUSEL (hidden on md and up) -->
      <div id="poster-carousel-mobile" class="relative w-full overflow-hidden rounded-none md:rounded-2xl shadow-lg md:hidden mb-8" role="region" aria-label="Carousel Poster Mobile">
        <div class="carousel-track-mobile relative w-full aspect-[16/5]">
          <div class="carousel-inner-mobile flex w-full h-full will-change-transform" style="min-width: 100%;">
          @foreach($posters as $i => $src)
            <div class="carousel-slide-mobile flex-shrink-0 w-full h-full relative" data-index="{{ $i }}" aria-hidden="{{ $i === 0 ? 'false' : 'true' }}">
              <img src="{{ $src }}" alt="Poster {{ $i + 1 }}" class="w-full h-full object-contain object-center bg-white" loading="{{ $i === 0 ? 'eager' : 'lazy' }}" decoding="async">
            </div>
          @endforeach
          </div>
        </div>

        <!-- Mobile Prev/Next Controls -->
        <button type="button" class="carousel-prev-mobile absolute left-3 top-1/2 -translate-y-1/2 z-10 inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/70 hover:bg-white/90 border border-gray-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2E529F]" aria-label="Sebelumnya">
          <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button type="button" class="carousel-next-mobile absolute right-3 top-1/2 -translate-y-1/2 z-10 inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/70 hover:bg-white/90 border border-gray-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2E529F]" aria-label="Berikutnya">
          <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </button>
      </div>

      <!-- Mobile Dots (selector) -->
      <div class="md:hidden -mt-4 mb-8 flex justify-center">
        <div class="carousel-dots-mobile flex justify-center gap-2" aria-hidden="false">
          @foreach($posters as $i => $src)
            <button type="button" class="carousel-dot-mobile w-2.5 h-2.5 rounded-full bg-gray-300/80 hover:bg-gray-400 border border-gray-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2E529F] transition-all duration-200" data-index="{{ $i }}" aria-label="Slide {{ $i + 1 }}" aria-pressed="false"></button>
          @endforeach
        </div>
      </div>

      <!-- DESKTOP CAROUSEL (hidden on md and below) -->
      <div id="poster-carousel-desktop" class="relative w-full overflow-hidden rounded-2xl shadow-lg hidden md:block" role="region" aria-label="Carousel Poster Desktop">
        <div class="carousel-track-desktop relative w-full aspect-[16/5]">
          <div class="carousel-inner-desktop flex w-full h-full will-change-transform rounded-2xl" style="min-width: 100%;">
          @foreach($posters as $i => $src)
            <div class="carousel-slide-desktop flex-shrink-0 w-full h-full relative" data-index="{{ $i }}" aria-hidden="{{ $i === 0 ? 'false' : 'true' }}">
              <img src="{{ $src }}" alt="Poster {{ $i + 1 }}" class="w-full h-full min-w-full min-h-full object-contain object-center bg-white" loading="{{ $i === 0 ? 'eager' : 'lazy' }}" decoding="async">
            </div>
          @endforeach
          </div>
        </div>

        <!-- Desktop Prev/Next Controls -->
        <button type="button" class="carousel-prev-desktop absolute left-4 top-1/2 -translate-y-1/2 z-10 inline-flex items-center justify-center w-11 h-11 rounded-full bg-white/70 hover:bg-white/90 border border-gray-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2E529F]" aria-label="Sebelumnya">
          <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button type="button" class="carousel-next-desktop absolute right-4 top-1/2 -translate-y-1/2 z-10 inline-flex items-center justify-center w-11 h-11 rounded-full bg-white/70 hover:bg-white/90 border border-gray-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2E529F]" aria-label="Berikutnya">
          <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </button>
      </div>

      <!-- Desktop Dots (selector) -->
      <div class="hidden md:flex -mt-4 mb-8 justify-center">
        <div class="carousel-dots-desktop flex justify-center gap-3" aria-hidden="false">
          @foreach($posters as $i => $src)
            <button type="button" class="carousel-dot-desktop w-3 h-3 md:w-3.5 md:h-3.5 rounded-full bg-gray-300/80 hover:bg-gray-400 border border-gray-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2E529F] transition-all duration-200" data-index="{{ $i }}" aria-label="Slide {{ $i + 1 }}" aria-pressed="false"></button>
          @endforeach
        </div>
      </div>
    </div>


  <script>
    // MOBILE CAROUSEL
    (function(){
      const inner = document.querySelector('#poster-carousel-mobile .carousel-inner-mobile');
      const slides = Array.from(document.querySelectorAll('#poster-carousel-mobile .carousel-slide-mobile'));
      const dots = Array.from(document.querySelectorAll('.carousel-dots-mobile .carousel-dot-mobile'));
      const prevBtn = document.querySelector('#poster-carousel-mobile .carousel-prev-mobile');
      const nextBtn = document.querySelector('#poster-carousel-mobile .carousel-next-mobile');
      let current = 0;
      let interval = null;

      const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

      if (!inner || slides.length === 0) {
        const el = document.getElementById('poster-carousel-mobile');
        if (el) el.style.display = 'none';
        return;
      }

      const total = slides.length;
      inner.style.width = `${total * 100}%`;
      slides.forEach(slide => {
        slide.style.width = `${100 / total}%`;
      });

      if(!prefersReduced){
        inner.style.transition = 'transform 700ms ease-in-out';
      } else {
        inner.style.transition = 'none';
      }

      inner.style.touchAction = 'pan-y';

      let startX = 0;
      let deltaX = 0;
      const threshold = 50;

      function stopAuto(){
        if(interval){
          clearInterval(interval);
          interval = null;
        }
      }

      function startAuto(){
        if(prefersReduced || total <= 1){ return; }
        stopAuto();
        interval = setInterval(nextSlide, 2000);
      }

      function onTouchStart(e){
        if (e.touches && e.touches.length) startX = e.touches[0].clientX;
        else if (e instanceof PointerEvent) startX = e.clientX;
        stopAuto();
      }

      function onTouchMove(e){
        if (!startX) return;
        const x = e.touches ? e.touches[0].clientX : e.clientX;
        deltaX = x - startX;
      }

      function onTouchEnd(){
        if (Math.abs(deltaX) > threshold){
          if (deltaX < 0) show(current + 1);
          else show(current - 1);
        }
        startX = 0; deltaX = 0;
        startAuto();
      }

      inner.addEventListener('touchstart', onTouchStart, {passive:true});
      inner.addEventListener('touchmove', onTouchMove, {passive:true});
      inner.addEventListener('touchend', onTouchEnd);
      inner.addEventListener('touchcancel', onTouchEnd);
      inner.addEventListener('pointerdown', onTouchStart);
      inner.addEventListener('pointerup', onTouchEnd);
      inner.addEventListener('pointercancel', onTouchEnd);

      function show(index){
        index = ((index % total) + total) % total;
        const offset = -(index * 100) / total;
        inner.style.transform = `translateX(${offset}%)`;
        slides.forEach((el,i)=> el.setAttribute('aria-hidden', i===index ? 'false' : 'true'));
        dots.forEach((d,i)=>{
          if(i===index){
            d.classList.remove('bg-gray-300/80','hover:bg-gray-400');
            d.classList.add('bg-[#2E529F]');
            d.setAttribute('aria-pressed','true');
          } else {
            d.classList.add('bg-gray-300/80','hover:bg-gray-400');
            d.classList.remove('bg-[#2E529F]');
            d.setAttribute('aria-pressed','false');
          }
        });
        current = index;
      }

      function nextSlide(){
        show(current + 1);
      }

      function prevSlide(){
        show(current - 1);
      }

      dots.forEach(d=> d.addEventListener('click', e => show(Number(e.currentTarget.dataset.index))));
      if (prevBtn) prevBtn.addEventListener('click', () => { stopAuto(); prevSlide(); startAuto(); });
      if (nextBtn) nextBtn.addEventListener('click', () => { stopAuto(); nextSlide(); startAuto(); });

      const carouselEl = document.getElementById('poster-carousel-mobile');
      carouselEl.addEventListener('mouseenter', stopAuto);
      carouselEl.addEventListener('mouseleave', startAuto);

      startAuto();
      show(0);
    })();

    // DESKTOP CAROUSEL
    (function(){
      const inner = document.querySelector('#poster-carousel-desktop .carousel-inner-desktop');
      const slides = Array.from(document.querySelectorAll('#poster-carousel-desktop .carousel-slide-desktop'));
      const dots = Array.from(document.querySelectorAll('.carousel-dots-desktop .carousel-dot-desktop'));
      const prevBtn = document.querySelector('#poster-carousel-desktop .carousel-prev-desktop');
      const nextBtn = document.querySelector('#poster-carousel-desktop .carousel-next-desktop');
      let current = 0;
      let interval = null;

      const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

      if (!inner || slides.length === 0) {
        const el = document.getElementById('poster-carousel-desktop');
        if (el) el.style.display = 'none';
        return;
      }

      const total = slides.length;
      inner.style.width = `${total * 100}%`;
      slides.forEach(slide => {
        slide.style.width = `${100 / total}%`;
      });

      if(!prefersReduced){
        inner.style.transition = 'transform 700ms ease-in-out';
      } else {
        inner.style.transition = 'none';
      }

      inner.style.touchAction = 'pan-y';

      let startX = 0;
      let deltaX = 0;
      const threshold = 50;

      function stopAuto(){
        if(interval){
          clearInterval(interval);
          interval = null;
        }
      }

      function startAuto(){
        if(prefersReduced || total <= 1){ return; }
        stopAuto();
        interval = setInterval(nextSlide, 2000);
      }

      function onTouchStart(e){
        if (e.touches && e.touches.length) startX = e.touches[0].clientX;
        else if (e instanceof PointerEvent) startX = e.clientX;
        stopAuto();
      }

      function onTouchMove(e){
        if (!startX) return;
        const x = e.touches ? e.touches[0].clientX : e.clientX;
        deltaX = x - startX;
      }

      function onTouchEnd(){
        if (Math.abs(deltaX) > threshold){
          if (deltaX < 0) show(current + 1);
          else show(current - 1);
        }
        startX = 0; deltaX = 0;
        startAuto();
      }

      inner.addEventListener('touchstart', onTouchStart, {passive:true});
      inner.addEventListener('touchmove', onTouchMove, {passive:true});
      inner.addEventListener('touchend', onTouchEnd);
      inner.addEventListener('touchcancel', onTouchEnd);
      inner.addEventListener('pointerdown', onTouchStart);
      inner.addEventListener('pointerup', onTouchEnd);
      inner.addEventListener('pointercancel', onTouchEnd);

      function show(index){
        index = ((index % total) + total) % total;
        const offset = -(index * 100) / total;
        inner.style.transform = `translateX(${offset}%)`;
        slides.forEach((el,i)=> el.setAttribute('aria-hidden', i===index ? 'false' : 'true'));
        dots.forEach((d,i)=>{
          if(i===index){
            d.classList.remove('bg-gray-300/80','hover:bg-gray-400');
            d.classList.add('bg-[#2E529F]');
            d.setAttribute('aria-pressed','true');
          } else {
            d.classList.add('bg-gray-300/80','hover:bg-gray-400');
            d.classList.remove('bg-[#2E529F]');
            d.setAttribute('aria-pressed','false');
          }
        });
        current = index;
      }

      function nextSlide(){
        show(current + 1);
      }

      function prevSlide(){
        show(current - 1);
      }

      dots.forEach(d=> d.addEventListener('click', e => show(Number(e.currentTarget.dataset.index))));
      if (prevBtn) prevBtn.addEventListener('click', () => { stopAuto(); prevSlide(); startAuto(); });
      if (nextBtn) nextBtn.addEventListener('click', () => { stopAuto(); nextSlide(); startAuto(); });

      const carouselEl = document.getElementById('poster-carousel-desktop');
      carouselEl.addEventListener('mouseenter', stopAuto);
      carouselEl.addEventListener('mouseleave', startAuto);

      startAuto();
      show(0);
    })();
  </script>
</section>
