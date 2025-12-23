<section class="relative bg-white">
  <div class="overflow-hidden">
    <div id="poster-carousel" class="relative w-full" role="region" aria-label="Carousel Poster">
      <div class="carousel-track relative w-full h-44 sm:h-60 md:h-[450px] lg:h-[560px]">
        <div class="carousel-inner flex w-full h-full will-change-transform rounded-2xl shadow-lg overflow-hidden">
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

        @foreach($posters as $i => $src)
          <div class="carousel-slide flex-shrink-0 w-full h-full relative" data-index="{{ $i }}" aria-hidden="{{ $i === 0 ? 'false' : 'true' }}">
            <img src="{{ $src }}" alt="Poster {{ $i + 1 }}" class="w-full h-full object-cover object-center" loading="{{ $i === 0 ? 'eager' : 'lazy' }}" decoding="async">

            <!-- CTA overlay on poster (center-left alignment, hidden on mobile) -->
            <div class="absolute inset-0 hidden md:flex items-center justify-center md:justify-start md:pl-10 pointer-events-none">
              <a href="{{ route('login') }}" class="pointer-events-auto px-5 py-2.5 rounded-lg bg-white/95 text-[#2E529F] font-medium shadow hover:shadow-lg hover:bg-white focus:ring-4 focus:ring-[#2E529F]/30 transition-all duration-150 ease-in-out">
                Daftar Sekarang
              </a>
            </div>

            <!-- Overlay masks to hide corner watermarks; keep them minimal so posters stay visible -->
            <div class="absolute inset-0 pointer-events-none">
              <div class="absolute top-0 left-0 w-28 h-20 md:w-36 md:h-24 rounded-br-xl" style="background:linear-gradient(135deg, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.0) 60%);"></div>
              <div class="absolute bottom-0 right-0 w-28 h-20 md:w-36 md:h-24 rounded-tl-xl" style="background:linear-gradient(-45deg, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.0) 60%);"></div>
              <!-- soft vignette for better contrast at edges -->
              <div class="absolute inset-0 bg-gradient-to-t from-black/6 via-transparent to-black/6"></div>
            </div>
          </div>
        @endforeach
        </div>
      </div>
    </div>

    <!-- Indicators (overlaid, centered bottom) -->
    <div class="absolute left-0 right-0 flex justify-center pointer-events-none">
      <div class="carousel-dots mt-0 mb-2 flex justify-center gap-3 pointer-events-auto" aria-hidden="false">
        @foreach($posters as $i => $src)
          <button class="carousel-dot w-3 h-3 sm:w-3 sm:h-3 md:w-3 md:h-3 rounded-full bg-white/60 border border-gray-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2E529F]" data-index="{{ $i }}" aria-label="Slide {{ $i + 1 }}" aria-pressed="false"></button>
        @endforeach
      </div>
    </div>
    </div>
  </div>

  <script>
    (function(){
      const inner = document.querySelector('#poster-carousel .carousel-inner');
      const slides = Array.from(document.querySelectorAll('#poster-carousel .carousel-slide'));
      const dots = Array.from(document.querySelectorAll('.carousel-dots .carousel-dot'));
      let current = 0;
      let interval = null;

      const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      const isMobile = window.matchMedia('(max-width: 767px)').matches;

      // If there are no slides, hide carousel area
      if (!inner || slides.length === 0) {
        const el = document.getElementById('poster-carousel');
        if (el) el.style.display = 'none';
        return;
      }

      // Set widths so translateX % calculations work predictably
      const total = slides.length;
      inner.style.width = `${total * 100}%`;
      slides.forEach(slide => {
        slide.style.width = `${100 / total}%`;
      });

      // set transition unless user prefers reduced motion
      if(!prefersReduced){
        inner.style.transition = 'transform 700ms ease-in-out';
      } else {
        inner.style.transition = 'none';
      }

      // Lock horizontal panning on mobile to keep centered view
      inner.style.touchAction = 'pan-y';

      // Basic swipe support
      let startX = 0;
      let deltaX = 0;
      const threshold = 50; // px to trigger swipe

      function stopAuto(){
        if(interval){
          clearInterval(interval);
          interval = null;
        }
      }

      function startAuto(){
        if(prefersReduced || total <= 1){ return; }
        stopAuto();
        interval = setInterval(nextSlide, 4000);
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
        index = ((index % total) + total) % total; // normalize
        const offset = -(index * 100) / total;
        inner.style.transform = `translateX(${offset}%)`;
        slides.forEach((el,i)=> el.setAttribute('aria-hidden', i===index ? 'false' : 'true'));
        dots.forEach((d,i)=>{
          if(i===index){
            d.classList.remove('bg-white/60','border-gray-200');
            d.classList.add('bg-[#2E529F]');
            d.setAttribute('aria-pressed','true');
          } else {
            d.classList.add('bg-white/60','border-gray-200');
            d.classList.remove('bg-[#2E529F]');
            d.setAttribute('aria-pressed','false');
          }
        });
        current = index;
      }

      function nextSlide(){
        show(current + 1);
      }

      // Attach dot click handlers safely
      dots.forEach(d=> d.addEventListener('click', e => show(Number(e.currentTarget.dataset.index))));

      const carouselEl = document.getElementById('poster-carousel');
      carouselEl.addEventListener('mouseenter', stopAuto);
      carouselEl.addEventListener('mouseleave', startAuto);

      // start autoplay if not reduced motion
      startAuto();

      // init
      show(0);
    })();
  </script>
</section>
