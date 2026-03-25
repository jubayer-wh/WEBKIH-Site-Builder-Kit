(function(){
    const cards = document.querySelectorAll('.wbk-sc-card');
    const frame = document.getElementById('wbkPreviewFrame');
    const toast = document.getElementById('wbkToast');

    if (!cards.length || !frame || !toast) {
        return;
    }

    const previewBase = frame.getAttribute('data-preview-base') || '';

    cards.forEach((card) => {
        card.addEventListener('click', () => {
            const sc = card.dataset.sc;
            if (!sc) {
                return;
            }

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(sc);
            }

            cards.forEach((c) => c.classList.remove('active'));
            card.classList.add('active');

            frame.src = previewBase + encodeURIComponent(sc);

            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 1200);
        });
    });
})();
