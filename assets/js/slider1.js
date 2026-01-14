/**
 * WBK Slider 1
 * Dependency-free hero slider
 * Safe for multiple instances & other sliders
 */

document.addEventListener('DOMContentLoaded', () => {
    const prefersReducedMotion =
        window.matchMedia &&
        window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    document.querySelectorAll('.wbk-tour-hero-swiper').forEach((sliderEl) => {
        if (sliderEl.dataset.wbkInitialized) return;
        sliderEl.dataset.wbkInitialized = '1';

        const slides = Array.from(sliderEl.querySelectorAll('.swiper-slide'));
        const pagination = sliderEl.querySelector('.wbk-swiper-pagination');

        if (!slides.length) return;

        let currentIndex = 0;
        let autoplayTimer = null;
        let isPaused = false;

        if (pagination) {
            pagination.innerHTML = '';
        }

        const bullets = slides.map((_, index) => {
            if (!pagination) return null;
            const bullet = document.createElement('span');
            bullet.className = 'swiper-pagination-bullet';
            bullet.addEventListener('click', () => {
                goToSlide(index, true);
            });
            pagination.appendChild(bullet);
            return bullet;
        });

        function setActive(index) {
            slides.forEach((slide, i) => {
                slide.classList.toggle('swiper-slide-active', i === index);
            });

            bullets.forEach((bullet, i) => {
                if (!bullet) return;
                bullet.classList.toggle('swiper-pagination-bullet-active', i === index);
            });
        }

        function animateSlideContent(root) {
            const activeSlide = root.querySelector('.swiper-slide-active');
            if (!activeSlide) return;

            const title = activeSlide.querySelector('h2');
            const text  = activeSlide.querySelector('p');
            const btn   = activeSlide.querySelector('.wbk-hero-cta');

            [title, text, btn].forEach((el, i) => {
                if (!el) return;

                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';

                requestAnimationFrame(() => {
                    el.style.transition =
                        'opacity 600ms ease, transform 600ms cubic-bezier(.2,.9,.2,1)';
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                    el.style.transitionDelay = `${i * 120}ms`;
                });
            });
        }

        function resetSlideContent(root) {
            const activeSlide = root.querySelector('.swiper-slide-active');
            if (!activeSlide) return;

            activeSlide.querySelectorAll('h2, p, .wbk-hero-cta').forEach((el) => {
                el.style.transition = 'none';
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
            });
        }

        function goToSlide(index, userInitiated = false) {
            resetSlideContent(sliderEl);
            currentIndex = (index + slides.length) % slides.length;
            setActive(currentIndex);
            animateSlideContent(sliderEl);

            if (userInitiated) {
                restartAutoplay();
            }
        }

        function nextSlide() {
            goToSlide(currentIndex + 1);
        }

        function startAutoplay() {
            if (prefersReducedMotion || slides.length < 2) return;
            autoplayTimer = setInterval(() => {
                if (!isPaused) {
                    nextSlide();
                }
            }, 5000);
        }

        function stopAutoplay() {
            if (autoplayTimer) {
                clearInterval(autoplayTimer);
                autoplayTimer = null;
            }
        }

        function restartAutoplay() {
            stopAutoplay();
            startAutoplay();
        }

        sliderEl.addEventListener('mouseenter', () => {
            isPaused = true;
        });

        sliderEl.addEventListener('mouseleave', () => {
            isPaused = false;
        });

        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                stopAutoplay();
            } else {
                restartAutoplay();
            }
        });

        setActive(0);
        animateSlideContent(sliderEl);
        startAutoplay();
    });
});
