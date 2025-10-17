/**
 * Carousel Controller
 * Handles auto-rotating carousel with manual controls
 *
 * Usage: Include this file in your blade template
 * <script src="{{ asset('js/carousel.js') }}"></script>
 */

class CarouselController {
    constructor(options = {}) {
        this.autoplayInterval = options.autoplayInterval || 5000;
        this.slides = document.querySelectorAll(".carousel-slide");
        this.dots = document.querySelectorAll(".carousel-dot");
        this.nextBtn = document.querySelector(".carousel-next");
        this.prevBtn = document.querySelector(".carousel-prev");
        this.container = document.querySelector(".carousel-container");

        this.currentSlide = 0;
        this.totalSlides = this.slides.length;
        this.autoplayTimer = null;

        this.init();
    }

    /**
     * Initialize carousel
     */
    init() {
        if (this.totalSlides === 0) {
            console.warn("No carousel slides found");
            return;
        }

        this.attachEventListeners();
        this.startAutoplay();
        this.showSlide(0);
    }

    /**
     * Attach event listeners to carousel controls
     */
    attachEventListeners() {
        // Next button
        if (this.nextBtn) {
            this.nextBtn.addEventListener("click", () => this.handleNext());
        }

        // Previous button
        if (this.prevBtn) {
            this.prevBtn.addEventListener("click", () => this.handlePrev());
        }

        // Dot navigation
        this.dots.forEach((dot, index) => {
            dot.addEventListener("click", () => this.handleDotClick(index));
        });

        // Pause on hover
        if (this.container) {
            this.container.addEventListener("mouseenter", () =>
                this.pauseAutoplay()
            );
            this.container.addEventListener("mouseleave", () =>
                this.resumeAutoplay()
            );
        }

        // Keyboard navigation
        document.addEventListener("keydown", (e) => this.handleKeyboard(e));
    }

    /**
     * Show specific slide
     * @param {number} index - Slide index to show
     */
    showSlide(index) {
        // Validate index
        if (index < 0) {
            this.currentSlide = this.totalSlides - 1;
        } else if (index >= this.totalSlides) {
            this.currentSlide = 0;
        } else {
            this.currentSlide = index;
        }

        // Update slides visibility
        this.slides.forEach((slide, i) => {
            if (i === this.currentSlide) {
                slide.classList.remove("opacity-0");
                slide.classList.add("opacity-100");
            } else {
                slide.classList.add("opacity-0");
                slide.classList.remove("opacity-100");
            }
        });

        // Update dot indicators
        this.dots.forEach((dot, i) => {
            if (i === this.currentSlide) {
                dot.classList.remove("opacity-50");
                dot.classList.add("opacity-100");
            } else {
                dot.classList.add("opacity-50");
                dot.classList.remove("opacity-100");
            }
        });
    }

    /**
     * Handle next button click
     */
    handleNext() {
        this.pauseAutoplay();
        this.showSlide(this.currentSlide + 1);
        this.resumeAutoplay();
    }

    /**
     * Handle previous button click
     */
    handlePrev() {
        this.pauseAutoplay();
        this.showSlide(this.currentSlide - 1);
        this.resumeAutoplay();
    }

    /**
     * Handle dot click
     * @param {number} index - Dot index clicked
     */
    handleDotClick(index) {
        this.pauseAutoplay();
        this.showSlide(index);
        this.resumeAutoplay();
    }

    /**
     * Handle keyboard navigation
     * @param {KeyboardEvent} event - Keyboard event
     */
    handleKeyboard(event) {
        if (event.key === "ArrowRight") {
            this.handleNext();
        } else if (event.key === "ArrowLeft") {
            this.handlePrev();
        }
    }

    /**
     * Start autoplay
     */
    startAutoplay() {
        this.autoplayTimer = setInterval(() => {
            this.showSlide(this.currentSlide + 1);
        }, this.autoplayInterval);
    }

    /**
     * Pause autoplay
     */
    pauseAutoplay() {
        if (this.autoplayTimer) {
            clearInterval(this.autoplayTimer);
        }
    }

    /**
     * Resume autoplay
     */
    resumeAutoplay() {
        this.pauseAutoplay();
        this.startAutoplay();
    }

    /**
     * Destroy carousel
     */
    destroy() {
        this.pauseAutoplay();
        // Remove event listeners if needed
    }
}

// Initialize carousel when DOM is ready
document.addEventListener("DOMContentLoaded", function () {
    if (document.querySelector(".carousel-container")) {
        window.carousel = new CarouselController({
            autoplayInterval: 5000, // 5 seconds
        });
    }
});
