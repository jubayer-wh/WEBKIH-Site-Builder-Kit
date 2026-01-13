<?php
if ( ! defined('ABSPATH') ) exit;
if ( ! current_user_can('manage_options') ) return;

$defaults = [
    'title'       => 'Visit WEBKIH',
    'address'     => 'Brudger Bazaar Rd, Atpara 2470',
    'email'       => 'jubayer@webkih.com',
    'hours'       => 'Sat - Thu | 9 AM - 8 PM',
    'button_text' => 'Get Directions',
    'button_url'  => 'https://maps.app.goo.gl/gNeAjCxHXWLsrKwE6',
    'iframe_src'  => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3126.8290194528977!2d90.85983747536879!3d24.794488377972446!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3756e70c3b3900b9%3A0x7498eb0a8b396f7c!2sWEBKIH!5e1!3m2!1sen!2sbd!4v1767615114491!5m2!1sen!2sbd'
];

$opt_key = 'wbk_map1_settings';

/*--------------------------------------------------------------
SAVE SETTINGS (SMART iframe HANDLER)
--------------------------------------------------------------*/
if ( isset($_POST['wbk_save_map1']) ) {

    check_admin_referer('wbk_save_map1_action', 'wbk_map1_nonce');

    // ---- SMART iframe SRC HANDLING ----
    $iframe_input = $_POST['iframe_src'] ?? '';
    $iframe_input = wp_unslash($iframe_input);

    // If full iframe pasted, extract src=""
    if ( preg_match('/src=["\']([^"\']+)["\']/', $iframe_input, $matches) ) {
        $iframe_input = $matches[1];
    }

    $data = [
        'title'       => sanitize_text_field($_POST['title'] ?? ''),
        'address'     => sanitize_text_field($_POST['address'] ?? ''),
        'email'       => sanitize_email($_POST['email'] ?? ''),
        'hours'       => sanitize_text_field($_POST['hours'] ?? ''),
        'button_text' => sanitize_text_field($_POST['button_text'] ?? ''),
        'button_url'  => esc_url_raw($_POST['button_url'] ?? ''),
        'iframe_src'  => esc_url_raw($iframe_input),
    ];

    update_option($opt_key, array_merge($defaults, $data));
    echo '<div class="notice notice-success"><p>Map settings saved.</p></div>';
}

$settings = get_option($opt_key, []);
$settings = array_merge($defaults, is_array($settings) ? $settings : []);
?>

<div class="wrap">
    <h1>Map (Style 1) Settings</h1>
    <p>These values will show in shortcode: <code>[wbk_map1]</code></p>

    <form method="post">
        <?php wp_nonce_field('wbk_save_map1_action', 'wbk_map1_nonce'); ?>

        <table class="form-table">

            <tr>
                <th><label for="title">Title</label></th>
                <td>
                    <input class="regular-text" id="title" name="title"
                           value="<?php echo esc_attr($settings['title']); ?>">
                </td>
            </tr>

            <tr>
                <th><label for="address">Address</label></th>
                <td>
                    <input class="regular-text" id="address" name="address"
                           value="<?php echo esc_attr($settings['address']); ?>">
                </td>
            </tr>

            <tr>
                <th><label for="email">Email</label></th>
                <td>
                    <input class="regular-text" id="email" name="email"
                           value="<?php echo esc_attr($settings['email']); ?>">
                </td>
            </tr>

            <tr>
                <th><label for="hours">Hours</label></th>
                <td>
                    <input class="regular-text" id="hours" name="hours"
                           value="<?php echo esc_attr($settings['hours']); ?>">
                </td>
            </tr>

            <tr>
                <th><label for="button_text">Button Text</label></th>
                <td>
                    <input class="regular-text" id="button_text" name="button_text"
                           value="<?php echo esc_attr($settings['button_text']); ?>">
                </td>
            </tr>

            <tr>
                <th><label for="button_url">Button URL</label></th>
                <td>
                    <input class="regular-text" id="button_url" name="button_url"
                           value="<?php echo esc_attr($settings['button_url']); ?>">
                </td>
            </tr>

            <tr>
                <th><label for="iframe_src">Google Map Embed</label></th>
                <td>
                    <textarea class="large-text" rows="4"
                              id="iframe_src" name="iframe_src"><?php
                        echo esc_textarea($settings['iframe_src']);
                    ?></textarea>
                    <p class="description">
                        Paste <strong>iframe src URL</strong> or the <strong>full iframe code</strong>.
                        The plugin will automatically extract the correct URL.
                    </p>
                </td>
            </tr>

        </table>

        <p>
            <button class="button button-primary" type="submit" name="wbk_save_map1">
                Save Map Settings
            </button>
        </p>
    </form>
</div>
