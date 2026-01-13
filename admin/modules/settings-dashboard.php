<?php
if ( ! defined('ABSPATH') ) exit;
if ( ! current_user_can('manage_options') ) return;

$opt_key = 'wbk_settings';

$defaults = [
    'brand_name'    => 'WEBKIH',
    'primary_color' => '#0e304c',
    'accent_color'  => '#38bdf8',
];

if ( isset($_POST['wbk_save_settings']) ) {
    check_admin_referer('wbk_save_settings_action', 'wbk_nonce');

    $brand_name_raw = sanitize_text_field( wp_unslash($_POST['brand_name'] ?? '') );
    $primary_raw    = sanitize_hex_color( wp_unslash($_POST['primary_color'] ?? '') );
    $accent_raw     = sanitize_hex_color( wp_unslash($_POST['accent_color'] ?? '') );

    $data = [
        'brand_name'    => $brand_name_raw !== '' ? $brand_name_raw : $defaults['brand_name'],
        'primary_color' => $primary_raw ?: $defaults['primary_color'],
        'accent_color'  => $accent_raw ?: $defaults['accent_color'],
    ];

    update_option($opt_key, $data, false);

    echo '<div class="notice notice-success is-dismissible"><p>Settings saved.</p></div>';
}

$settings = get_option($opt_key, []);
$settings = array_merge($defaults, is_array($settings) ? $settings : []);

?>

<style>
    :root{
        --wbk-primary: <?php echo esc_attr($settings['primary_color']); ?>;
        --wbk-accent: <?php echo esc_attr($settings['accent_color']); ?>;
        --wbk-border: #e5e7eb;
        --wbk-muted: #64748b;
        --wbk-card: #ffffff;
        --wbk-bg: #f8fafc;
    }

    .wbk-settings-wrap{
        max-width: 980px;
        margin: 18px 0;
    }

    .wbk-settings-hero{
        background: linear-gradient(135deg, var(--wbk-primary), #022b55);
        border-radius: 16px;
        padding: 18px 20px;
        color: #fff;
        box-shadow: 0 16px 35px rgba(1, 28, 57, 0.18);
        margin-bottom: 14px;
        position: relative;
        overflow: hidden;
    }

    .wbk-settings-hero::after{
        content:"";
        position:absolute;
        inset:-40%;
        background: radial-gradient(circle at 30% 30%, rgba(56,189,248,0.35), transparent 55%);
        pointer-events:none;
        transform: rotate(10deg);
    }

    .wbk-settings-hero h1{
        margin: 0;
        font-size: 1.45rem;
        font-weight: 900;
        letter-spacing: -0.3px;
        color: #ffffff;
    }

    .wbk-settings-hero p{
        margin: 6px 0 0;
        font-size: .92rem;
        opacity: .9;
    }

    .wbk-card{
        background: var(--wbk-card);
        border: 1px solid var(--wbk-border);
        border-radius: 14px;
        padding: 16px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.04);
    }

    .wbk-grid{
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-top: 12px;
    }

    .wbk-field label{
        display:block;
        font-weight: 800;
        margin-bottom: 6px;
        color: #0f172a;
    }

    .wbk-field input[type="text"]{
        width: 100%;
        border-radius: 10px;
        border: 1px solid var(--wbk-border);
        padding: 10px 12px;
        font-size: 14px;
        outline: none;
        transition: 0.2s ease;
        background: #fff;
    }

    .wbk-field input[type="text"]:focus{
        border-color: var(--wbk-accent);
        box-shadow: 0 0 0 3px rgba(56,189,248,0.25);
    }

    .wbk-help{
        margin-top: 6px;
        font-size: 12px;
        color: var(--wbk-muted);
    }

    .wbk-row{
        display:flex;
        gap: 10px;
        align-items:center;
    }

    .wbk-color-preview{
        width: 34px;
        height: 34px;
        border-radius: 10px;
        border: 1px solid var(--wbk-border);
        background: #fff;
        box-shadow: inset 0 0 0 2px rgba(0,0,0,0.02);
    }

    .wbk-actions{
        display:flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 14px;
    }

    .wbk-btn{
        border: 1px solid transparent;
        border-radius: 10px;
        padding: 10px 14px;
        font-weight: 800;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .wbk-btn-primary{
        background: var(--wbk-accent);
        color: #001a2e;
    }

    .wbk-btn-primary:hover{
        transform: translateY(-1px);
        box-shadow: 0 12px 22px rgba(56,189,248,0.25);
    }

    .wbk-btn-ghost{
        background: #fff;
        border-color: var(--wbk-border);
        color: #0f172a;
    }

    .wbk-btn-ghost:hover{
        border-color: var(--wbk-accent);
        box-shadow: 0 0 0 3px rgba(56,189,248,0.15);
    }

    @media (max-width: 860px){
        .wbk-grid{ grid-template-columns: 1fr; }
        .wbk-actions{ justify-content: stretch; }
        .wbk-btn{ width: 100%; }
    }
</style>

<div class="wrap wbk-settings-wrap">
    <div class="wbk-settings-hero">
        <h1><?php echo esc_html($settings['brand_name']); ?> Kit Settings</h1>
        <p>Set your brand name and colors. These can be reused across modules for a consistent look.</p>
    </div>

    <div class="wbk-card">
        <form method="post" novalidate>
            <?php wp_nonce_field('wbk_save_settings_action', 'wbk_nonce'); ?>

            <div class="wbk-grid">
                <div class="wbk-field">
                    <label for="brand_name">Brand Name</label>
                    <input type="text" id="brand_name" name="brand_name"
                           value="<?php echo esc_attr($settings['brand_name']); ?>"
                           placeholder="e.g. WEBKIH">
                    <div class="wbk-help">Used on admin pages and future templates.</div>
                </div>

                <div class="wbk-field">
                    <label>Primary Color</label>
                    <div class="wbk-row">
                        <div class="wbk-color-preview" id="wbkPrimaryPreview" style="background: <?php echo esc_attr($settings['primary_color']); ?>;"></div>
                        <input type="text" id="primary_color" name="primary_color"
                               value="<?php echo esc_attr($settings['primary_color']); ?>"
                               placeholder="#0e304c">
                    </div>
                    <div class="wbk-help">Main brand color. Use hex like <code>#0e304c</code>.</div>
                </div>

                <div class="wbk-field">
                    <label>Accent Color</label>
                    <div class="wbk-row">
                        <div class="wbk-color-preview" id="wbkAccentPreview" style="background: <?php echo esc_attr($settings['accent_color']); ?>;"></div>
                        <input type="text" id="accent_color" name="accent_color"
                               value="<?php echo esc_attr($settings['accent_color']); ?>"
                               placeholder="#38bdf8">
                    </div>
                    <div class="wbk-help">Highlights, buttons, neon accents. Hex only.</div>
                </div>

                <div class="wbk-field">
                    <label>Quick Presets</label>
                    <div class="wbk-row" style="flex-wrap:wrap;">
                        <button type="button" class="wbk-btn wbk-btn-ghost" data-preset="classic">WEBKIH Classic</button>
                        <button type="button" class="wbk-btn wbk-btn-ghost" data-preset="neon">Neon Night</button>
                        <button type="button" class="wbk-btn wbk-btn-ghost" data-preset="calm">Calm Blue</button>
                    </div>
                    <div class="wbk-help">Optional: quickly switch brand palettes before saving.</div>
                </div>
            </div>

            <div class="wbk-actions">
                <button type="button" class="wbk-btn wbk-btn-ghost" id="wbkResetDefaults">Reset to Defaults</button>
                <button type="submit" name="wbk_save_settings" class="wbk-btn wbk-btn-primary">Save Settings</button>
            </div>
        </form>
    </div>
</div>

<script>
(function(){
    const primary = document.getElementById('primary_color');
    const accent  = document.getElementById('accent_color');
    const pPrev   = document.getElementById('wbkPrimaryPreview');
    const aPrev   = document.getElementById('wbkAccentPreview');
    const resetBtn = document.getElementById('wbkResetDefaults');

    const defaults = {
        brand: "WEBKIH",
        primary: "#0e304c",
        accent: "#38bdf8"
    };

    function isHex(v){
        return /^#[0-9a-fA-F]{6}$/.test((v || "").trim());
    }

    function updatePreview(){
        if (isHex(primary.value)) pPrev.style.background = primary.value.trim();
        if (isHex(accent.value))  aPrev.style.background = accent.value.trim();
    }

    primary.addEventListener('input', updatePreview);
    accent.addEventListener('input', updatePreview);

    document.querySelectorAll('[data-preset]').forEach(btn=>{
        btn.addEventListener('click', ()=>{
            const preset = btn.getAttribute('data-preset');

            if (preset === 'classic'){
                primary.value = "#0e304c";
                accent.value  = "#38bdf8";
            }
            if (preset === 'neon'){
                primary.value = "#011c39";
                accent.value  = "#00f5ff";
            }
            if (preset === 'calm'){
                primary.value = "#0b2a4a";
                accent.value  = "#7dd3fc";
            }
            updatePreview();
        });
    });

    resetBtn.addEventListener('click', ()=>{
        const brand = document.getElementById('brand_name');
        brand.value = defaults.brand;
        primary.value = defaults.primary;
        accent.value = defaults.accent;
        updatePreview();
    });

    updatePreview();
})();
</script>
