<?php
if ( ! defined('ABSPATH') ) exit;
if ( ! current_user_can('manage_options') ) return;

$webksibu_opt_key = 'webksibu_settings';

$webksibu_defaults = [
    'brand_name'    => 'WEBKIH',
    'primary_color' => '#0e304c',
    'accent_color'  => '#38bdf8',
];

if ( isset($_POST['webksibu_save_settings']) ) {
    $webksibu_nonce = isset($_POST['webksibu_nonce']) ? sanitize_text_field( wp_unslash($_POST['webksibu_nonce']) ) : '';
    if ( ! wp_verify_nonce($webksibu_nonce, 'webksibu_save_settings_action') ) {
        wp_die( esc_html__('Security check failed.', 'webkih-site-builder-kit') );
    }

    $webksibu_brand_name_raw = sanitize_text_field( wp_unslash($_POST['brand_name'] ?? '') );
    $webksibu_primary_raw    = sanitize_hex_color( wp_unslash($_POST['primary_color'] ?? '') );
    $webksibu_accent_raw     = sanitize_hex_color( wp_unslash($_POST['accent_color'] ?? '') );

    $webksibu_data = [
        'brand_name'    => $webksibu_brand_name_raw !== '' ? $webksibu_brand_name_raw : $webksibu_defaults['brand_name'],
        'primary_color' => $webksibu_primary_raw ?: $webksibu_defaults['primary_color'],
        'accent_color'  => $webksibu_accent_raw ?: $webksibu_defaults['accent_color'],
    ];

    update_option($webksibu_opt_key, $webksibu_data, false);

    echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Settings saved.', 'webkih-site-builder-kit') . '</p></div>';
}

$webksibu_settings = get_option($webksibu_opt_key, []);
$webksibu_settings = array_merge($webksibu_defaults, is_array($webksibu_settings) ? $webksibu_settings : []);

?>


<div class="wrap webksibu-settings-wrap">
    <div class="webksibu-settings-hero">
        <h1><?php echo esc_html($webksibu_settings['brand_name']); ?> Kit Settings</h1>
        <p>Set your brand name and colors. These can be reused across modules for a consistent look.</p>
    </div>

    <div class="webksibu-card">
        <form method="post" novalidate>
            <?php wp_nonce_field('webksibu_save_settings_action', 'webksibu_nonce'); ?>

            <div class="webksibu-grid">
                <div class="webksibu-field">
                    <label for="brand_name">Brand Name</label>
                    <input type="text" id="brand_name" name="brand_name"
                           value="<?php echo esc_attr($webksibu_settings['brand_name']); ?>"
                           placeholder="e.g. WEBKIH">
                    <div class="webksibu-help">Used on admin pages and future templates.</div>
                </div>

                <div class="webksibu-field">
                    <label>Primary Color</label>
                    <div class="webksibu-row">
                        <div class="webksibu-color-preview" id="webksibuPrimaryPreview" data-color="<?php echo esc_attr($webksibu_settings['primary_color']); ?>" style="background: <?php echo esc_attr($webksibu_settings['primary_color']); ?>;"></div>
                        <input type="text" id="primary_color" name="primary_color"
                               value="<?php echo esc_attr($webksibu_settings['primary_color']); ?>"
                               placeholder="#0e304c">
                    </div>
                    <div class="webksibu-help">Main brand color. Use hex like <code>#0e304c</code>.</div>
                </div>

                <div class="webksibu-field">
                    <label>Accent Color</label>
                    <div class="webksibu-row">
                        <div class="webksibu-color-preview" id="webksibuAccentPreview" data-color="<?php echo esc_attr($webksibu_settings['accent_color']); ?>" style="background: <?php echo esc_attr($webksibu_settings['accent_color']); ?>;"></div>
                        <input type="text" id="accent_color" name="accent_color"
                               value="<?php echo esc_attr($webksibu_settings['accent_color']); ?>"
                               placeholder="#38bdf8">
                    </div>
                    <div class="webksibu-help">Highlights, buttons, neon accents. Hex only.</div>
                </div>

                <div class="webksibu-field">
                    <label>Quick Presets</label>
                    <div class="webksibu-row webksibu-row-wrap">
                        <button type="button" class="webksibu-btn webksibu-btn-ghost" data-preset="classic">WEBKIH Classic</button>
                        <button type="button" class="webksibu-btn webksibu-btn-ghost" data-preset="neon">Neon Night</button>
                        <button type="button" class="webksibu-btn webksibu-btn-ghost" data-preset="calm">Calm Blue</button>
                    </div>
                    <div class="webksibu-help">Optional: quickly switch brand palettes before saving.</div>
                </div>
            </div>

            <div class="webksibu-actions">
                <button type="button" class="webksibu-btn webksibu-btn-ghost" id="webksibuResetDefaults">Reset to Defaults</button>
                <button type="submit" name="webksibu_save_settings" class="webksibu-btn webksibu-btn-primary">Save Settings</button>
            </div>
        </form>
    </div>
</div>
