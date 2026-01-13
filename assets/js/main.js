// Admin: copy shortcode button
document.addEventListener("click", function(e){
  const btn = e.target.closest(".wbk-copy-btn");
  if(!btn) return;

  const text = btn.getAttribute("data-copy") || "";
  navigator.clipboard.writeText(text).then(() => {
    btn.textContent = "Copied!";
    setTimeout(() => (btn.textContent = "Copy"), 1200);
  });
});

// Frontend: simple slider rotation (for .wbk-slider1)
document.addEventListener("DOMContentLoaded", function(){
  document.querySelectorAll(".wbk-slider1").forEach((slider) => {
    const slides = slider.querySelectorAll(".wbk-slide");
    if(slides.length <= 1) return;

    let i = 0;
    slides.forEach((s, idx) => s.style.display = idx === 0 ? "block" : "none");

    setInterval(() => {
      slides[i].style.display = "none";
      i = (i + 1) % slides.length;
      slides[i].style.display = "block";
    }, 3500);
  });
});
