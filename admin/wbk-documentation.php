<?php
if ( ! defined('ABSPATH') ) exit;
?>

<style>
:root{
    --wbk-primary:#011c39;
    --wbk-neon:#38bdf8;
    --wbk-bg:#f8fafc;
    --wbk-card:#ffffff;
    --wbk-border:#e5e7eb;
    --wbk-muted:#64748b;
}

.wbk-doc-wrap{
    max-width:1200px;
    margin:15px auto;
    font-family:Inter,system-ui,-apple-system,sans-serif;
}

.wbk-doc-header{
    background:linear-gradient(135deg,var(--wbk-primary),#022b55);
    color:#fff;
    border-radius:14px;
    padding:18px 22px;
    margin-bottom:16px;
}

.wbk-doc-header h1{
    margin:0;
    font-size:1.6rem;
    font-weight:800;
    color: #ffffff;
}

.wbk-doc-header p{
    margin:4px 0 0;
    opacity:.85;
    font-size:.9rem;
}

.wbk-doc-layout{
    display:grid;
    grid-template-columns:300px 1fr; /* was 420px */
    gap:12px;
}

.wbk-doc-left{
    background:linear-gradient(180deg,#ffffff,#f9fbff);
}


.wbk-doc-left,
.wbk-doc-right{
    background:var(--wbk-card);
    border:1px solid var(--wbk-border);
    border-radius:14px;
    padding:10px;
}

.wbk-doc-left h2,
.wbk-doc-right h2{
    margin:0 0 10px;
    font-size:1.1rem;
    font-weight:800;
    color:var(--wbk-primary);
}

/* Shortcode cards */
.wbk-sc-grid{
    display:grid;
    grid-template-columns:1fr;
    gap:8px;
}

.wbk-sc-card{
    border:1px solid var(--wbk-border);
    border-radius:10px;
    padding:8px 10px;
    cursor:pointer;
    transition:.2s ease;
}

.wbk-sc-card:hover,
.wbk-sc-card.active{
    border-color:var(--wbk-neon);
    box-shadow:0 0 0 2px rgba(56,189,248,.25);
}

.wbk-sc-code{
    font-family:ui-monospace,monospace;
    font-size:.85rem;
    font-weight:700;
    background:rgba(56,189,248,.12);
    padding:4px 8px;
    border-radius:6px;
    display:inline-block;
}

.wbk-sc-desc{
    font-size:.8rem;
    color:var(--wbk-muted);
    margin-top:4px;
}

/* Preview */
.wbk-preview-frame{
    width:100%;
    height:600px;
    border:1px solid var(--wbk-border);
    border-radius:12px;
    background:#fff;
}

.wbk-preview-note{
    font-size:.8rem;
    color:var(--wbk-muted);
    margin-bottom:8px;
}

/* Toast */
.wbk-toast{
    position:fixed;
    bottom:18px;
    right:18px;
    background:var(--wbk-primary);
    color:#fff;
    padding:8px 14px;
    border-radius:999px;
    font-size:.8rem;
    opacity:0;
    transform:translateY(8px);
    transition:.3s ease;
    z-index:9999;
}
.wbk-toast.show{opacity:1;transform:none;}

@media(max-width:980px){
    .wbk-doc-layout{grid-template-columns:1fr;}
}
@media (min-width:1400px){
    .wbk-preview-frame{ height:680px; }
}

</style>

<div class="wbk-doc-wrap">

    <!-- Header -->
    <div class="wbk-doc-header">
        <h1>WEBKIH Kit Documentation</h1>
        <p>Click a shortcode → auto copy → live preview</p>
    </div>

    <div class="wbk-doc-layout">

        <!-- LEFT: Shortcodes -->
        <div class="wbk-doc-left">
            <h2>Shortcodes</h2>

            <div class="wbk-sc-grid">

                <div class="wbk-sc-card active" data-sc="[wbk_slider1]">
                    <span class="wbk-sc-code">[wbk_slider1]</span>
                    <div class="wbk-sc-desc">Slider 1</div>
                </div>

                <div class="wbk-sc-card" data-sc="[wbk_slider2]">
                    <span class="wbk-sc-code">[wbk_slider2]</span>
                    <div class="wbk-sc-desc">Slider 2</div>
                </div>

                <div class="wbk-sc-card" data-sc="[wbk_team1]">
                    <span class="wbk-sc-code">[wbk_team1]</span>
                    <div class="wbk-sc-desc">Team members 1</div>
                </div>

                <div class="wbk-sc-card" data-sc="[wbk_map1]">
                    <span class="wbk-sc-code">[wbk_map1]</span>
                    <div class="wbk-sc-desc">Map Block 1</div>
                </div>

                <div class="wbk-sc-card" data-sc="[wbk_success1_3]">
                    <span class="wbk-sc-code">[wbk_success1_3]</span>
                    <div class="wbk-sc-desc">Success stories 1</div>
                </div>

                <div class="wbk-sc-card" data-sc="[wbk_package1]">
                    <span class="wbk-sc-code">[wbk_package1]</span>
                    <div class="wbk-sc-desc">Packages 1</div>
                </div>

            </div>
        </div>

        <!-- RIGHT: Live Preview -->
        <div class="wbk-doc-right">
            <h2>Live Preview</h2>
            <div class="wbk-preview-note">
                Preview loads using a sandbox page on your site.
            </div>

            <iframe
                id="wbkPreviewFrame"
                class="wbk-preview-frame"
                src="<?php echo esc_url( home_url( '?wbk_preview=[wbk_slider1]' ) ); ?>">
            </iframe>
        </div>

    </div>
</div>

<div class="wbk-toast" id="wbkToast">Shortcode copied</div>

<script>
(function(){
    const cards = document.querySelectorAll('.wbk-sc-card');
    const frame = document.getElementById('wbkPreviewFrame');
    const toast = document.getElementById('wbkToast');

    cards.forEach(card=>{
        card.addEventListener('click',()=>{
            const sc = card.dataset.sc;

            // Copy
            navigator.clipboard.writeText(sc);

            // UI active state
            cards.forEach(c=>c.classList.remove('active'));
            card.classList.add('active');

            // Update preview
            frame.src = "<?php echo esc_js( home_url( '?wbk_preview=' ) ); ?>" + encodeURIComponent(sc);

            // Toast
            toast.classList.add('show');
            setTimeout(()=>toast.classList.remove('show'),1200);
        });
    });
})();
</script>
