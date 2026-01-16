document.addEventListener("DOMContentLoaded", () => {
  const frame = document.getElementById("wbkPreviewFrame");
  const toast = document.getElementById("wbkToast");
  const cards = document.querySelectorAll(".wbk-sc-card");

  if (!frame || !cards.length || !window.WBK_DOCS) return;

  const { previewBase, nonce } = window.WBK_DOCS;

  const showToast = () => {
    if (!toast) return;
    toast.classList.add("show");
    setTimeout(() => toast.classList.remove("show"), 1200);
  };

  const setActive = (card) => {
    cards.forEach(c => c.classList.remove("active"));
    card.classList.add("active");
  };

  const buildPreviewUrl = (shortcode) => {
    const url = new URL(previewBase, window.location.origin);
    url.searchParams.set("wbk_preview", shortcode);
    url.searchParams.set("_wbk_nonce", nonce);
    return url.toString();
  };

  cards.forEach(card => {
    card.addEventListener("click", () => {
      const sc = card.getAttribute("data-sc") || "";
      if (!sc) return;

      // copy
      navigator.clipboard?.writeText(sc).catch(() => {});

      // active UI
      setActive(card);
      showToast();

      // update preview
      frame.src = buildPreviewUrl(sc);
    });
  });
});
