/**
 * WBK Slider 1
 * Swiper-based hero slider
 * Safe for multiple instances & other sliders
 */

document.addEventListener('DOMContentLoaded', () => {

    // Respect reduced motion preferences
    const prefersReducedMotion =
        window.matchMedia &&
        window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    document.querySelectorAll('.wbk-tour-hero-swiper').forEach((sliderEl) => {

        // Prevent double init
        if (sliderEl.dataset.wbkInitialized) return;
        sliderEl.dataset.wbkInitialized = '1';

        const swiper = new Swiper(sliderEl, {
            loop: true,
            speed: prefersReducedMotion ? 300 : 1000,
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },

            autoplay: prefersReducedMotion ? false : {
                delay: 5000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true
            },

            pagination: {
                el: sliderEl.querySelector('.wbk-swiper-pagination'),
                clickable: true
            },

            on: {
                init() {
                    animateSlideContent(sliderEl);
                },
                slideChangeTransitionStart() {
                    resetSlideContent(sliderEl);
                },
                slideChangeTransitionEnd() {
                    animateSlideContent(sliderEl);
                }
            }
        });

        /* --------------------------------
           Content animations
        -------------------------------- */
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

        /* --------------------------------
           Pause autoplay on tab hidden
        -------------------------------- */
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                swiper.autoplay && swiper.autoplay.stop();
            } else {
                swiper.autoplay && swiper.autoplay.start();
            }
        });

    });

});
