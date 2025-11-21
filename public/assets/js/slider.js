class SliderManager {
    constructor() {
        this.sliders = [];
        this.init();
    }

    init() {
        this.initializeSliders();
        this.setupEventListeners();
    }

    initializeSliders() {
        // Инициализация верхнего слайдера
        const topSlider = document.querySelector('.top-slider');
        if (topSlider) {
            this.sliders.push(new Slider(topSlider, {
                autoplay: true,
                interval: 5000,
                transition: 'slide'
            }));
        }

        // Инициализация нижнего слайдера
        const bottomSlider = document.querySelector('.bottom-slider');
        if (bottomSlider) {
            this.sliders.push(new Slider(bottomSlider, {
                autoplay: true,
                interval: 7000,
                transition: 'fade'
            }));
        }
    }

    setupEventListeners() {
        // Пауза при наведении
        this.sliders.forEach(slider => {
            slider.element.addEventListener('mouseenter', () => {
                slider.pause();
            });

            slider.element.addEventListener('mouseleave', () => {
                slider.resume();
            });
        });

        // Обработка видимости страницы
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.sliders.forEach(slider => slider.pause());
            } else {
                this.sliders.forEach(slider => slider.resume());
            }
        });
    }
}

class Slider {
    constructor(element, options = {}) {
        this.element = element;
        this.slides = element.querySelectorAll('.slide');
        this.dotsContainer = element.querySelector('.slider-dots');
        this.prevBtn = element.querySelector('.slider-prev');
        this.nextBtn = element.querySelector('.slider-next');
        
        this.options = {
            autoplay: options.autoplay || false,
            interval: options.interval || 5000,
            transition: options.transition || 'slide',
            ...options
        };

        this.currentIndex = 0;
        this.isAnimating = false;
        this.autoplayInterval = null;

        this.init();
    }

    init() {
        if (this.slides.length === 0) return;

        this.createDots();
        this.showSlide(0);
        this.setupEventListeners();
        
        if (this.options.autoplay) {
            this.startAutoplay();
        }
    }

    createDots() {
        if (!this.dotsContainer) return;

        this.dotsContainer.innerHTML = '';
        
        this.slides.forEach((_, index) => {
            const dot = document.createElement('button');
            dot.className = `slider-dot ${index === 0 ? 'active' : ''}`;
            dot.setAttribute('aria-label', `Перейти к слайду ${index + 1}`);
            dot.addEventListener('click', () => this.goToSlide(index));
            this.dotsContainer.appendChild(dot);
        });
    }

    setupEventListeners() {
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => this.prevSlide());
        }

        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => this.nextSlide());
        }

        // Touch events для мобильных устройств
        let touchStartX = 0;
        let touchEndX = 0;

        this.element.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });

        this.element.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            this.handleSwipe(touchStartX, touchEndX);
        });

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (document.activeElement.closest('.slider') === this.element) {
                if (e.key === 'ArrowLeft') this.prevSlide();
                if (e.key === 'ArrowRight') this.nextSlide();
            }
        });
    }

    handleSwipe(startX, endX) {
        const swipeThreshold = 50;
        const diff = startX - endX;

        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                this.nextSlide();
            } else {
                this.prevSlide();
            }
        }
    }

    showSlide(index) {
        if (this.isAnimating || index < 0 || index >= this.slides.length) return;

        this.isAnimating = true;

        // Скрываем текущий слайд
        this.slides[this.currentIndex].classList.remove('active');
        this.updateDots(this.currentIndex, false);

        // Показываем новый слайд
        this.currentIndex = index;
        this.slides[this.currentIndex].classList.add('active');
        this.updateDots(this.currentIndex, true);

        // Обновляем ARIA attributes
        this.updateAccessibility();

        setTimeout(() => {
            this.isAnimating = false;
        }, 500);
    }

    updateDots(index, isActive) {
        const dots = this.dotsContainer?.querySelectorAll('.slider-dot');
        if (dots) {
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === index);
            });
        }
    }

    updateAccessibility() {
        this.slides.forEach((slide, index) => {
            slide.setAttribute('aria-hidden', index !== this.currentIndex);
        });

        this.element.setAttribute('aria-live', 'polite');
    }

    nextSlide() {
        const nextIndex = (this.currentIndex + 1) % this.slides.length;
        this.showSlide(nextIndex);
    }

    prevSlide() {
        const prevIndex = this.currentIndex === 0 ? this.slides.length - 1 : this.currentIndex - 1;
        this.showSlide(prevIndex);
    }

    goToSlide(index) {
        this.showSlide(index);
    }

    startAutoplay() {
        this.stopAutoplay();
        this.autoplayInterval = setInterval(() => {
            this.nextSlide();
        }, this.options.interval);
    }

    stopAutoplay() {
        if (this.autoplayInterval) {
            clearInterval(this.autoplayInterval);
            this.autoplayInterval = null;
        }
    }

    pause() {
        this.stopAutoplay();
    }

    resume() {
        if (this.options.autoplay) {
            this.startAutoplay();
        }
    }

    destroy() {
        this.stopAutoplay();
        // Cleanup event listeners
        if (this.prevBtn) {
            this.prevBtn.replaceWith(this.prevBtn.cloneNode(true));
        }
        if (this.nextBtn) {
            this.nextBtn.replaceWith(this.nextBtn.cloneNode(true));
        }
    }
}

// Инициализация при загрузке DOM
document.addEventListener('DOMContentLoaded', () => {
    new SliderManager();
});

// Export для использования в других модулях
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { SliderManager, Slider };
}