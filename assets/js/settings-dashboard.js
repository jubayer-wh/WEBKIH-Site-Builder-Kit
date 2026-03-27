(function(){
    const primary = document.getElementById('primary_color');
    const accent  = document.getElementById('accent_color');
    const pPrev   = document.getElementById('webksibuPrimaryPreview');
    const aPrev   = document.getElementById('webksibuAccentPreview');
    const resetBtn = document.getElementById('webksibuResetDefaults');

    if (!primary || !accent || !pPrev || !aPrev || !resetBtn) {
        return;
    }

    const defaults = {
        brand: 'WEBKIH',
        primary: '#0e304c',
        accent: '#38bdf8'
    };

    function isHex(v){
        return /^#[0-9a-fA-F]{6}$/.test((v || '').trim());
    }

    function updatePreview(){
        if (isHex(primary.value)) pPrev.style.background = primary.value.trim();
        if (isHex(accent.value))  aPrev.style.background = accent.value.trim();
    }

    primary.addEventListener('input', updatePreview);
    accent.addEventListener('input', updatePreview);

    document.querySelectorAll('[data-preset]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const preset = btn.getAttribute('data-preset');

            if (preset === 'classic') {
                primary.value = '#0e304c';
                accent.value  = '#38bdf8';
            }
            if (preset === 'neon') {
                primary.value = '#011c39';
                accent.value  = '#00f5ff';
            }
            if (preset === 'calm') {
                primary.value = '#0b2a4a';
                accent.value  = '#7dd3fc';
            }
            updatePreview();
        });
    });

    resetBtn.addEventListener('click', () => {
        const brand = document.getElementById('brand_name');
        if (brand) {
            brand.value = defaults.brand;
        }
        primary.value = defaults.primary;
        accent.value = defaults.accent;
        updatePreview();
    });

    updatePreview();
})();
