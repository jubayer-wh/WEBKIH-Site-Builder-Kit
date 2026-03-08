document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.cs-slider1').forEach(function (slider) {
    var slides = slider.querySelectorAll('.cs-slide');
    if (slides.length <= 1) {
      return;
    }

    var speed = parseInt(slider.getAttribute('data-speed') || '3500', 10);
    var current = 0;

    window.setInterval(function () {
      slides[current].classList.remove('is-active');
      current = (current + 1) % slides.length;
      slides[current].classList.add('is-active');
    }, Math.max(speed, 1500));
  });
});
