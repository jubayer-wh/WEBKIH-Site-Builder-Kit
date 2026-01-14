/**
 * WBK Slider2 (Modern) – smooth snap + animated text + autoplay + swipe
 * Requires existing HTML structure + your slider2.css
 * Works with multiple sliders on same page.
 *
 * Animation style:
 * - Slide snaps instantly (no heavy scroll-jank)
 * - Text overlay animates in/out (fade + translate + slight blur)
 * - Dots update precisely
 * - Autoplay pauses on hover/focus/drag, resumes after interaction
 */

document.addEventListener('DOMContentLoaded', () => {

  document.querySelectorAll('[data-wbk-slider2="1"]').forEach((slider) => {

    const viewport = slider.querySelector('.wbk-slider2-viewport');
    const slides   = Array.from(slider.querySelectorAll('.wbk-slider2-slide'));
    const dots     = Array.from(slider.querySelectorAll('.wbk-slider2-dot'));
    const nextBtn  = slider.querySelector('.wbk-slider2-next');
    const prevBtn  = slider.querySelector('.wbk-slider2-prev');

    if (!viewport || slides.length === 0) return;

    // ---- settings (tune these)
    const AUTOPLAY_DELAY = 4200;
    const RESUME_AFTER_INTERACTION_MS = 2500;

    // ---- state
    let index = 0;
    let autoplayTimer = null;
    let resumeTimer = null;
    let isDragging = false;

    // reduce motion support
    const prefersReducedMotion = window.matchMedia &&
      window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Make sure viewport snaps (helps modern feel)
    viewport.style.scrollSnapType = 'x mandatory';
    viewport.style.scrollBehavior = prefersReducedMotion ? 'auto' : 'smooth';

    // -------------------------------------------
    // Helpers
    // -------------------------------------------
    const slideWidth = () => viewport.clientWidth;

    const clampIndex = (i) => {
      const n = slides.length;
      return (i % n + n) % n;
    };

    const getIndexFromScroll = () => {
      const w = slideWidth() || 1;
      return clampIndex(Math.round(viewport.scrollLeft / w));
    };

    const setActiveDot = (i) => {
      dots.forEach((dot, di) => dot.classList.toggle('active', di === i));
    };

    const animateOverlay = (i, direction = 'next') => {
      // Animate only the overlay content; keeps slider super smooth.
      slides.forEach((s, si) => {
        const content = s.querySelector('.wbk-slider2-slide-content');
        if (!content) return;

        const isActive = si === i;

        // Prep base
        content.style.willChange = 'transform, opacity, filter';

        if (prefersReducedMotion) {
          content.style.opacity = isActive ? '1' : '0';
          content.style.transform = 'translate3d(0,0,0)';
          content.style.filter = 'none';
          return;
        }

        if (isActive) {
          // In
          content.style.transition = 'none';
          content.style.opacity = '0';
          content.style.filter = 'blur(6px)';
          content.style.transform = `translate3d(${direction === 'next' ? '18px' : '-18px'}, 10px, 0)`;

          requestAnimationFrame(() => {
            content.style.transition = 'opacity 520ms ease, transform 620ms cubic-bezier(.2,.9,.2,1), filter 620ms ease';
            content.style.opacity = '1';
            content.style.filter = 'blur(0px)';
            content.style.transform = 'translate3d(0,0,0)';
          });
        } else {
          // Out (subtle, avoids flicker)
          content.style.transition = 'opacity 260ms ease';
          content.style.opacity = '0';
        }
      });
    };

    const goTo = (i, direction = 'next', userInitiated = false) => {
      index = clampIndex(i);

      // Update dots now (instant feedback)
      setActiveDot(index);

      // Animate overlay (modern feel)
      animateOverlay(index, direction);

      // Scroll viewport to slide
      const left = index * slideWidth();
      viewport.scrollTo({ left, behavior: prefersReducedMotion ? 'auto' : 'smooth' });

      // If user interacted, pause autoplay briefly then resume
      if (userInitiated) {
        pauseAutoplay();
        scheduleResume();
      }
    };

    // -------------------------------------------
    // Autoplay control
    // -------------------------------------------
    const startAutoplay = () => {
      if (prefersReducedMotion) return;
      stopAutoplay();
      autoplayTimer = setInterval(() => {
        goTo(index + 1, 'next', false);
      }, AUTOPLAY_DELAY);
    };

    const stopAutoplay = () => {
      if (autoplayTimer) {
        clearInterval(autoplayTimer);
        autoplayTimer = null;
      }
    };

    const pauseAutoplay = () => stopAutoplay();

    const scheduleResume = () => {
      if (prefersReducedMotion) return;
      if (resumeTimer) clearTimeout(resumeTimer);
      resumeTimer = setTimeout(() => startAutoplay(), RESUME_AFTER_INTERACTION_MS);
    };

    // -------------------------------------------
    // Buttons
    // -------------------------------------------
    if (nextBtn) nextBtn.addEventListener('click', () => goTo(index + 1, 'next', true));
    if (prevBtn) prevBtn.addEventListener('click', () => goTo(index - 1, 'prev', true));

    // -------------------------------------------
    // Dots
    // -------------------------------------------
    dots.forEach((dot, di) => {
      dot.addEventListener('click', () => {
        const dir = di > index ? 'next' : 'prev';
        goTo(di, dir, true);
      });
    });

    // -------------------------------------------
    // Scroll syncing (if user swipes/scrolls)
    // -------------------------------------------
    let raf = null;
    viewport.addEventListener('scroll', () => {
      if (raf) cancelAnimationFrame(raf);
      raf = requestAnimationFrame(() => {
        const newIndex = getIndexFromScroll();
        if (newIndex !== index) {
          const dir = newIndex > index ? 'next' : 'prev';
          index = newIndex;
          setActiveDot(index);
          animateOverlay(index, dir);
        }
      });
    });

    // -------------------------------------------
    // Touch/drag detection (pause autoplay while dragging)
    // -------------------------------------------
    const onPointerDown = () => {
      isDragging = true;
      pauseAutoplay();
    };
    const onPointerUp = () => {
      if (!isDragging) return;
      isDragging = false;
      scheduleResume();
    };

    viewport.addEventListener('pointerdown', onPointerDown, { passive: true });
    window.addEventListener('pointerup', onPointerUp, { passive: true });

    // Pause on hover/focus (nice UX)
    slider.addEventListener('mouseenter', pauseAutoplay);
    slider.addEventListener('mouseleave', scheduleResume);

    slider.addEventListener('focusin', pauseAutoplay);
    slider.addEventListener('focusout', scheduleResume);

    // -------------------------------------------
    // Responsive: keep current slide aligned on resize
    // -------------------------------------------
    window.addEventListener('resize', () => {
      // snap back to current slide on resize to avoid half-slide
      viewport.scrollTo({ left: index * slideWidth(), behavior: 'auto' });
    });

    // -------------------------------------------
    // Init
    // -------------------------------------------
    index = getIndexFromScroll();
    setActiveDot(index);
    animateOverlay(index, 'next');
    startAutoplay();
  });

});
