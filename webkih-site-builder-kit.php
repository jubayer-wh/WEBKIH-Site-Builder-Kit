<?php
/**
 * Plugin Name: WEBKIH Site Builder Kit
 * Plugin URI: https://github.com/jubayer-wh/WEBKIH-Site-Builder-Kit
 * Description: GH8-FWG1.0.0 - Modular website sections (Hero, Slider, Team, Packages, Contact, Loader) with shortcodes + admin gallery.
 * Version: 1.0.0
 * Author: Jubayer Hossain
 * Author URI: https://webkih.com
 * License: GPLv2 or later
 * Text Domain: webkih-site-builder-kit
 */

if ( ! defined('ABSPATH') ) exit;

define('WBK_VER', '1.0.0');
define('WBK_DIR', plugin_dir_path(__FILE__));
define('WBK_URL', plugin_dir_url(__FILE__));

add_action('plugins_loaded', function () {
    load_plugin_textdomain(
        'webkih-site-builder-kit',
        false,
        dirname(plugin_basename(__FILE__)) . '/languages'
    );
});

// Core: assets + admin menu
require_once WBK_DIR . 'admin/core.php';

// Addons (shortcodes)
require_once WBK_DIR . 'includes/wbk-slider1.php';
require_once WBK_DIR . 'includes/wbk-slider2.php';
require_once WBK_DIR . 'includes/wbk-team1.php';
require_once WBK_DIR . 'includes/wbk-map1.php';
require_once WBK_DIR . 'includes/wbk-success1.php';
require_once WBK_DIR . 'includes/wbk-package1.php';



// Frontend preview handler
add_action('template_redirect', function () {

    if ( ! isset($_GET['wbk_preview']) ) {
        return;
    }

    // Only admins should preview inside wp-admin iframe
    if ( ! current_user_can('manage_options') ) {
        status_header(403);
        exit;
    }

    $raw = sanitize_text_field( wp_unslash($_GET['wbk_preview']) );

    /**
     * Security: whitelist ONLY your plugin shortcodes
     * We accept either:
     *  - wbk_success1_3
     *  - [wbk_success1_3]
     *  - wbk_success1_3 cat="x"
     *  - [wbk_success1_3 cat="x"]
     */
    $raw = trim($raw);

    // If they passed just "wbk_xxx", wrap it
    if ( $raw !== '' && $raw[0] !== '[' ) {
        $raw = '[' . $raw . ']';
    }

    if ( ! preg_match('/^\[([a-z0-9_]+)(\s+[^\]]+)?\]$/i', $raw, $matches) ) {
        status_header(400);
        echo esc_html__('Invalid preview shortcode.', 'webkih-site-builder-kit');
        exit;
    }

    $allowed_shortcodes = [
        'wbk_map1',
        'wbk_slider1',
        'wbk_slider2',
        'wbk_team1',
        'wbk_success1_3',
        'wbk_success1_all',
        'wbk_package1',
    ];

    if ( ! in_array( strtolower($matches[1]), $allowed_shortcodes, true ) ) {
        status_header(400);
        echo esc_html__('Invalid preview shortcode.', 'webkih-site-builder-kit');
        exit;
    }

    // Load WP scripts/styles that shortcodes enqueue
    nocache_headers();

    // Minimal preview page
    ?>
    <!doctype html>
    <html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php
        /**
         * Important:
         * - This will output enqueued CSS/JS from your shortcode (wp_enqueue_style calls)
         * - Also outputs theme styles (fine for accurate preview)
         */
        wp_head();
        ?>
        <style>
            /* Keep preview clean and responsive */
            body{ margin:0; padding:18px; background:#fff; }
            img{ max-width:100%; height:auto; }
        </style>
    </head>
    <body>
        <?php echo do_shortcode( $raw ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <?php wp_footer(); ?>
    </body>
    </html>
    <?php
    exit;
});




