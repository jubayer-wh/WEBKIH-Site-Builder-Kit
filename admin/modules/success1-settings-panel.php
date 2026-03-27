<?php
if ( ! defined('ABSPATH') ) exit;
if ( ! current_user_can('manage_options') ) return;

$webksibu_opt_key = 'webksibu_success1_settings';

$webksibu_defaults = [
    'title'        => 'Our Recent Success Stories',
    'caption'      => 'Real people, real visas, and realized dreams.',
    'hide_heading' => 0,
    'deep_blue'     => '#002e63',
    'accent_blue'   => '#0056b3',
    'success_green' => '#27ae60',
    'light_gray'    => '#f4f7f9',
    'max_columns'    => 3,
    'min_card_width' => 350,
];

if ( isset($_POST['webksibu_success1_save']) ) {
    $webksibu_nonce = isset($_POST['webksibu_success1_nonce']) ? sanitize_text_field( wp_unslash($_POST['webksibu_success1_nonce']) ) : '';
    if ( ! wp_verify_nonce($webksibu_nonce, 'webksibu_success1_save_action') ) {
        wp_die( esc_html__('Security check failed.', 'webkih-site-builder-kit') );
    }

    $webksibu_title        = sanitize_text_field( wp_unslash($_POST['title'] ?? '') );
    $webksibu_caption      = sanitize_text_field( wp_unslash($_POST['caption'] ?? '') );
    $webksibu_hide_heading = isset($_POST['hide_heading']) ? 1 : 0;

    $webksibu_deep_blue     = sanitize_hex_color( wp_unslash($_POST['deep_blue'] ?? '') );
    $webksibu_accent_blue   = sanitize_hex_color( wp_unslash($_POST['accent_blue'] ?? '') );
    $webksibu_success_green = sanitize_hex_color( wp_unslash($_POST['success_green'] ?? '') );
    $webksibu_light_gray    = sanitize_hex_color( wp_unslash($_POST['light_gray'] ?? '') );

    $webksibu_max_columns    = absint( wp_unslash($_POST['max_columns'] ?? $webksibu_defaults['max_columns']) );
    $webksibu_min_card_width = absint( wp_unslash($_POST['min_card_width'] ?? $webksibu_defaults['min_card_width']) );

    $webksibu_max_columns = max(1, min(6, $webksibu_max_columns));
    $webksibu_min_card_width = max(220, min(600, $webksibu_min_card_width));

    $webksibu_data = [
        'title'        => $webksibu_title !== '' ? $webksibu_title : $webksibu_defaults['title'],
        'caption'      => $webksibu_caption !== '' ? $webksibu_caption : $webksibu_defaults['caption'],
        'hide_heading' => $webksibu_hide_heading,
        'deep_blue'     => $webksibu_deep_blue ?: $webksibu_defaults['deep_blue'],
        'accent_blue'   => $webksibu_accent_blue ?: $webksibu_defaults['accent_blue'],
        'success_green' => $webksibu_success_green ?: $webksibu_defaults['success_green'],
        'light_gray'    => $webksibu_light_gray ?: $webksibu_defaults['light_gray'],
        'max_columns'    => $webksibu_max_columns,
        'min_card_width' => $webksibu_min_card_width,
    ];

    update_option($webksibu_opt_key, $webksibu_data, false);
    echo '<div class="notice notice-success"><p>' . esc_html__('Success Stories settings saved.', 'webkih-site-builder-kit') . '</p></div>';
}

$webksibu_settings = get_option($webksibu_opt_key, []);
$webksibu_settings = array_merge($webksibu_defaults, is_array($webksibu_settings) ? $webksibu_settings : []);
?>

<h2 style="margin-top:0;">Success Stories (Style 1)</h2>
<p class="description">Affects: <code>[webksibu_success1_3]</code> and <code>[webksibu_success1_all]</code></p>

<form method="post">
    <?php wp_nonce_field('webksibu_success1_save_action', 'webksibu_success1_nonce'); ?>

    <h3>Heading</h3>
    <table class="form-table">
        <tr>
            <th><label for="title">Section Title</label></th>
            <td><input class="regular-text" id="title" name="title" value="<?php echo esc_attr($webksibu_settings['title']); ?>"></td>
        </tr>
        <tr>
            <th><label for="caption">Section Caption</label></th>
            <td><input class="large-text" id="caption" name="caption" value="<?php echo esc_attr($webksibu_settings['caption']); ?>"></td>
        </tr>
        <tr>
            <th>Hide Heading</th>
            <td>
                <label>
                    <input type="checkbox" name="hide_heading" <?php checked((int)$webksibu_settings['hide_heading'], 1); ?>>
                    Hide the title + caption
                </label>
            </td>
        </tr>
    </table>

    <hr>

    <h3>Colors (Hex)</h3>
    <table class="form-table">
        <tr><th><label for="deep_blue">Deep Blue</label></th><td><input class="regular-text" id="deep_blue" name="deep_blue" value="<?php echo esc_attr($webksibu_settings['deep_blue']); ?>"></td></tr>
        <tr><th><label for="accent_blue">Accent Blue</label></th><td><input class="regular-text" id="accent_blue" name="accent_blue" value="<?php echo esc_attr($webksibu_settings['accent_blue']); ?>"></td></tr>
        <tr><th><label for="success_green">Success Green</label></th><td><input class="regular-text" id="success_green" name="success_green" value="<?php echo esc_attr($webksibu_settings['success_green']); ?>"></td></tr>
        <tr><th><label for="light_gray">Light Gray</label></th><td><input class="regular-text" id="light_gray" name="light_gray" value="<?php echo esc_attr($webksibu_settings['light_gray']); ?>"></td></tr>
    </table>

    <hr>

    <h3>Layout</h3>
    <table class="form-table">
        <tr>
            <th><label for="max_columns">Max Columns (Desktop)</label></th>
            <td><input type="number" id="max_columns" name="max_columns" min="1" max="6" value="<?php echo esc_attr((int)$webksibu_settings['max_columns']); ?>"></td>
        </tr>
        <tr>
            <th><label for="min_card_width">Min Card Width (px)</label></th>
            <td><input type="number" id="min_card_width" name="min_card_width" min="220" max="600" value="<?php echo esc_attr((int)$webksibu_settings['min_card_width']); ?>"></td>
        </tr>
    </table>

    <p>
        <button class="button button-primary" type="submit" name="webksibu_success1_save">Save</button>
    </p>
</form>
