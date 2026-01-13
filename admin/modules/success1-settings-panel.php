<?php
if ( ! defined('ABSPATH') ) exit;
if ( ! current_user_can('manage_options') ) return;

$opt_key = 'wbk_success1_settings';

$defaults = [
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

if ( isset($_POST['wbk_success1_save']) ) {
    check_admin_referer('wbk_success1_save_action', 'wbk_success1_nonce');

    $title        = sanitize_text_field( wp_unslash($_POST['title'] ?? '') );
    $caption      = sanitize_text_field( wp_unslash($_POST['caption'] ?? '') );
    $hide_heading = isset($_POST['hide_heading']) ? 1 : 0;

    $deep_blue     = sanitize_hex_color( wp_unslash($_POST['deep_blue'] ?? '') );
    $accent_blue   = sanitize_hex_color( wp_unslash($_POST['accent_blue'] ?? '') );
    $success_green = sanitize_hex_color( wp_unslash($_POST['success_green'] ?? '') );
    $light_gray    = sanitize_hex_color( wp_unslash($_POST['light_gray'] ?? '') );

    $max_columns    = absint($_POST['max_columns'] ?? $defaults['max_columns']);
    $min_card_width = absint($_POST['min_card_width'] ?? $defaults['min_card_width']);

    $max_columns = max(1, min(6, $max_columns));
    $min_card_width = max(220, min(600, $min_card_width));

    $data = [
        'title'        => $title !== '' ? $title : $defaults['title'],
        'caption'      => $caption !== '' ? $caption : $defaults['caption'],
        'hide_heading' => $hide_heading,
        'deep_blue'     => $deep_blue ?: $defaults['deep_blue'],
        'accent_blue'   => $accent_blue ?: $defaults['accent_blue'],
        'success_green' => $success_green ?: $defaults['success_green'],
        'light_gray'    => $light_gray ?: $defaults['light_gray'],
        'max_columns'    => $max_columns,
        'min_card_width' => $min_card_width,
    ];

    update_option($opt_key, $data, false);
    echo '<div class="notice notice-success"><p>Success Stories settings saved.</p></div>';
}

$settings = get_option($opt_key, []);
$settings = array_merge($defaults, is_array($settings) ? $settings : []);
?>

<h2 style="margin-top:0;">Success Stories (Style 1)</h2>
<p class="description">Affects: <code>[wbk_success1_3]</code> and <code>[wbk_success1_all]</code></p>

<form method="post">
    <?php wp_nonce_field('wbk_success1_save_action', 'wbk_success1_nonce'); ?>

    <h3>Heading</h3>
    <table class="form-table">
        <tr>
            <th><label for="title">Section Title</label></th>
            <td><input class="regular-text" id="title" name="title" value="<?php echo esc_attr($settings['title']); ?>"></td>
        </tr>
        <tr>
            <th><label for="caption">Section Caption</label></th>
            <td><input class="large-text" id="caption" name="caption" value="<?php echo esc_attr($settings['caption']); ?>"></td>
        </tr>
        <tr>
            <th>Hide Heading</th>
            <td>
                <label>
                    <input type="checkbox" name="hide_heading" <?php checked((int)$settings['hide_heading'], 1); ?>>
                    Hide the title + caption
                </label>
            </td>
        </tr>
    </table>

    <hr>

    <h3>Colors (Hex)</h3>
    <table class="form-table">
        <tr><th><label for="deep_blue">Deep Blue</label></th><td><input class="regular-text" id="deep_blue" name="deep_blue" value="<?php echo esc_attr($settings['deep_blue']); ?>"></td></tr>
        <tr><th><label for="accent_blue">Accent Blue</label></th><td><input class="regular-text" id="accent_blue" name="accent_blue" value="<?php echo esc_attr($settings['accent_blue']); ?>"></td></tr>
        <tr><th><label for="success_green">Success Green</label></th><td><input class="regular-text" id="success_green" name="success_green" value="<?php echo esc_attr($settings['success_green']); ?>"></td></tr>
        <tr><th><label for="light_gray">Light Gray</label></th><td><input class="regular-text" id="light_gray" name="light_gray" value="<?php echo esc_attr($settings['light_gray']); ?>"></td></tr>
    </table>

    <hr>

    <h3>Layout</h3>
    <table class="form-table">
        <tr>
            <th><label for="max_columns">Max Columns (Desktop)</label></th>
            <td><input type="number" id="max_columns" name="max_columns" min="1" max="6" value="<?php echo esc_attr((int)$settings['max_columns']); ?>"></td>
        </tr>
        <tr>
            <th><label for="min_card_width">Min Card Width (px)</label></th>
            <td><input type="number" id="min_card_width" name="min_card_width" min="220" max="600" value="<?php echo esc_attr((int)$settings['min_card_width']); ?>"></td>
        </tr>
    </table>

    <p>
        <button class="button button-primary" type="submit" name="wbk_success1_save">Save</button>
    </p>
</form>
