<?php
if ( ! defined('ABSPATH') ) exit;

if ( ! current_user_can('manage_options') ) return;

$defaults = [
    'brand_name' => 'WEBKIH',
    'primary_color' => '#0e304c',
    'accent_color'  => '#38bdf8',
];

if ( isset($_POST['wbk_save_settings']) && check_admin_referer('wbk_save_settings_action', 'wbk_nonce') ) {
    $data = [
        'brand_name'    => sanitize_text_field($_POST['brand_name'] ?? ''),
        'primary_color' => sanitize_hex_color($_POST['primary_color'] ?? ''),
        'accent_color'  => sanitize_hex_color($_POST['accent_color'] ?? ''),
    ];
    update_option('wbk_settings', array_merge($defaults, $data));
    echo '<div class="notice notice-success"><p>Settings saved.</p></div>';
}

$settings = get_option('wbk_settings', $defaults);
$settings = array_merge($defaults, is_array($settings) ? $settings : []);
?>

<div class="wrap">
    <h1>WEBKIH Kit Settings</h1>

    <form method="post">
        <?php wp_nonce_field('wbk_save_settings_action', 'wbk_nonce'); ?>

        <table class="form-table">
            <tr>
                <th><label for="brand_name">Brand Name</label></th>
                <td><input type="text" id="brand_name" name="brand_name" value="<?php echo esc_attr($settings['brand_name']); ?>" class="regular-text"></td>
            </tr>

            <tr>
                <th><label for="primary_color">Primary Color</label></th>
                <td><input type="text" id="primary_color" name="primary_color" value="<?php echo esc_attr($settings['primary_color']); ?>" class="regular-text"></td>
            </tr>

            <tr>
                <th><label for="accent_color">Accent Color</label></th>
                <td><input type="text" id="accent_color" name="accent_color" value="<?php echo esc_attr($settings['accent_color']); ?>" class="regular-text"></td>
            </tr>
        </table>

        <p>
            <button type="submit" name="wbk_save_settings" class="button button-primary">Save Settings</button>
        </p>
    </form>
</div>
