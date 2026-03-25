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
    $nonce = isset($_POST['wbk_nonce']) ? sanitize_text_field( wp_unslash($_POST['wbk_nonce']) ) : '';
    if ( ! wp_verify_nonce($nonce, 'wbk_save_settings_action') ) {
        wp_die( esc_html__('Security check failed.', 'webkih-site-builder-kit') );
    }

    $brand_name_raw = sanitize_text_field( wp_unslash($_POST['brand_name'] ?? '') );
    $primary_raw    = sanitize_hex_color( wp_unslash($_POST['primary_color'] ?? '') );
    $accent_raw     = sanitize_hex_color( wp_unslash($_POST['accent_color'] ?? '') );

    $data = [
        'brand_name'    => $brand_name_raw !== '' ? $brand_name_raw : $defaults['brand_name'],
        'primary_color' => $primary_raw ?: $defaults['primary_color'],
        'accent_color'  => $accent_raw ?: $defaults['accent_color'],
    ];

    update_option($opt_key, $data, false);

    echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Settings saved.', 'webkih-site-builder-kit') . '</p></div>';
}

$settings = get_option($opt_key, []);
$settings = array_merge($defaults, is_array($settings) ? $settings : []);

?>


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
                        <div class="wbk-color-preview" id="wbkPrimaryPreview" data-color="<?php echo esc_attr($settings['primary_color']); ?>" style="background: <?php echo esc_attr($settings['primary_color']); ?>;"></div>
                        <input type="text" id="primary_color" name="primary_color"
                               value="<?php echo esc_attr($settings['primary_color']); ?>"
                               placeholder="#0e304c">
                    </div>
                    <div class="wbk-help">Main brand color. Use hex like <code>#0e304c</code>.</div>
                </div>

                <div class="wbk-field">
                    <label>Accent Color</label>
                    <div class="wbk-row">
                        <div class="wbk-color-preview" id="wbkAccentPreview" data-color="<?php echo esc_attr($settings['accent_color']); ?>" style="background: <?php echo esc_attr($settings['accent_color']); ?>;"></div>
                        <input type="text" id="accent_color" name="accent_color"
                               value="<?php echo esc_attr($settings['accent_color']); ?>"
                               placeholder="#38bdf8">
                    </div>
                    <div class="wbk-help">Highlights, buttons, neon accents. Hex only.</div>
                </div>

                <div class="wbk-field">
                    <label>Quick Presets</label>
                    <div class="wbk-row wbk-row-wrap">
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

