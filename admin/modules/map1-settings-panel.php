<?php
if ( ! defined('ABSPATH') ) exit;
if ( ! current_user_can('manage_options') ) return;

$defaults = [
    'title'       => 'Visit WEBKIH',
    'address'     => 'Brudger Bazaar Rd, Atpara 2470',
    'email'       => 'jubayer@webkih.com',
    'hours'       => 'Sat - Thu | 9 AM - 8 PM',
    'button_text' => 'Get Directions',

    // ✅ Avoid URL shorteners (goo.gl / maps.app.goo.gl)
    // Use a full URL instead.
    'button_url'  => 'https://www.google.com/maps?q=WEBKIH',

    'iframe_src'  => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3126.8290194528977!2d90.85983747536879!3d24.794488377972446!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3756e70c3b3900b9%3A0x7498eb0a8b396f7c!2sWEBKIH!5e1!3m2!1sen!2sbd!4v1767615114491!5m2!1sen!2sbd'
];

$opt_key = 'wbk_map1_settings';

/*--------------------------------------------------------------
SAVE SETTINGS (SMART iframe HANDLER)
--------------------------------------------------------------*/
if ( isset($_POST['wbk_save_map1']) ) {

    check_admin_referer('wbk_save_map1_action', 'wbk_map1_nonce');

    // ✅ Read inputs safely (PluginCheck friendly)
    $raw_title       = filter_input(INPUT_POST, 'title', FILTER_UNSAFE_RAW);
    $raw_address     = filter_input(INPUT_POST, 'address', FILTER_UNSAFE_RAW);
    $raw_email       = filter_input(INPUT_POST, 'email', FILTER_UNSAFE_RAW);
    $raw_hours       = filter_input(INPUT_POST, 'hours', FILTER_UNSAFE_RAW);
    $raw_button_text = filter_input(INPUT_POST, 'button_text', FILTER_UNSAFE_RAW);
    $raw_button_url  = filter_input(INPUT_POST, 'button_url', FILTER_UNSAFE_RAW);
    $raw_iframe_src  = filter_input(INPUT_POST, 'iframe_src', FILTER_UNSAFE_RAW);

    // ---- SMART iframe SRC HANDLING ----
    $iframe_input = is_string($raw_iframe_src) ? wp_unslash($raw_iframe_src) : '';

    // If full iframe pasted, extract src=""
    if ( $iframe_input !== '' && preg_match('/src=["\']([^"\']+)["\']/', $iframe_input, $matches) ) {
        $iframe_input = $matches[1];
    }

    $data = [
        'title'       => sanitize_text_field( is_string($raw_title) ? wp_unslash($raw_title) : '' ),
        'address'     => sanitize_text_field( is_string($raw_address) ? wp_unslash($raw_address) : '' ),
        'email'       => sanitize_email( is_string($raw_email) ? wp_unslash($raw_email) : '' ),
        'hours'       => sanitize_text_field( is_string($raw_hours) ? wp_unslash($raw_hours) : '' ),
        'button_text' => sanitize_text_field( is_string($raw_button_text) ? wp_unslash($raw_button_text) : '' ),
        'button_url'  => esc_url_raw( is_string($raw_button_url) ? wp_unslash($raw_button_url) : '' ),
        'iframe_src'  => esc_url_raw( $iframe_input ),
    ];

    update_option($opt_key, array_merge($defaults, $data));

    echo '<div class="notice notice-success"><p>' . esc_html__('Map settings saved.', 'webkih-site-builder-kit') . '</p></div>';
}

$settings = get_option($opt_key, []);
$settings = array_merge($defaults, is_array($settings) ? $settings : []);
?>

<div class="wrap">
    <h1><?php echo esc_html__('Map (Style 1) Settings', 'webkih-site-builder-kit'); ?></h1>
    <p><?php echo esc_html__('These values will show in shortcode:', 'webkih-site-builder-kit'); ?> <code>[wbk_map1]</code></p>

    <form method="post">
        <?php wp_nonce_field('wbk_save_map1_action', 'wbk_map1_nonce'); ?>

        <table class="form-table">

            <tr>
                <th><label for="title"><?php echo esc_html__('Title', 'webkih-site-builder-kit'); ?></label></th>
                <td>
                    <input class="regular-text" id="title" name="title"
                           value="<?php echo esc_attr($settings['title']); ?>">
                </td>
            </tr>

            <tr>
                <th><label for="address"><?php echo esc_html__('Address', 'webkih-site-builder-kit'); ?></label></th>
                <td>
                    <input class="regular-text" id="address" name="address"
                           value="<?php echo esc_attr($settings['address']); ?>">
                </td>
            </tr>

            <tr>
                <th><label for="email"><?php echo esc_html__('Email', 'webkih-site-builder-kit'); ?></label></th>
                <td>
                    <input class="regular-text" id="email" name="email"
                           value="<?php echo esc_attr($settings['email']); ?>">
                </td>
            </tr>

            <tr>
                <th><label for="hours"><?php echo esc_html__('Hours', 'webkih-site-builder-kit'); ?></label></th>
                <td>
                    <input class="regular-text" id="hours" name="hours"
                           value="<?php echo esc_attr($settings['hours']); ?>">
                </td>
            </tr>

            <tr>
                <th><label for="button_text"><?php echo esc_html__('Button Text', 'webkih-site-builder-kit'); ?></label></th>
                <td>
                    <input class="regular-text" id="button_text" name="button_text"
                           value="<?php echo esc_attr($settings['button_text']); ?>">
                </td>
            </tr>

            <tr>
                <th><label for="button_url"><?php echo esc_html__('Button URL', 'webkih-site-builder-kit'); ?></label></th>
                <td>
                    <input class="regular-text" id="button_url" name="button_url"
                           value="<?php echo esc_attr($settings['button_url']); ?>">
                    <p class="description">
                        <?php echo esc_html__('Use a full URL (avoid short links like goo.gl or maps.app.goo.gl).', 'webkih-site-builder-kit'); ?>
                    </p>
                </td>
            </tr>

            <tr>
                <th><label for="iframe_src"><?php echo esc_html__('Google Map Embed', 'webkih-site-builder-kit'); ?></label></th>
                <td>
                    <textarea class="large-text" rows="4" id="iframe_src" name="iframe_src"><?php
                        echo esc_textarea($settings['iframe_src']);
                    ?></textarea>
                    <p class="description">
                        <?php echo esc_html__('Paste iframe src URL or the full iframe code. The plugin will extract the src automatically.', 'webkih-site-builder-kit'); ?>
                    </p>
                </td>
            </tr>

        </table>

        <p>
            <button class="button button-primary" type="submit" name="wbk_save_map1">
                <?php echo esc_html__('Save Map Settings', 'webkih-site-builder-kit'); ?>
            </button>
        </p>
    </form>
</div>
