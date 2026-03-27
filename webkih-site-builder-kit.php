<?php
/**
 * Plugin Name: WEBKIH Site Builder Kit
 * Plugin URI: https://github.com/jubayer-wh/WEBKIH-Site-Builder-Kit
 * Description: GH8-FWG1.0.0 - Modular website sections (Hero, Slider, Team, Packages, Contact, Loader) with shortcodes + admin gallery.
 * Version: 1.0.0
 * Author: Jubayer Hossain
 * Author URI: https://www.webkih.com/about/
 * License: GPLv2 or later
 * Text Domain: webkih-site-builder-kit
 */

if ( ! defined('ABSPATH') ) exit;

define('WEBKSIBU_VER', '1.0.0');
define('WEBKSIBU_DIR', plugin_dir_path(__FILE__));
define('WEBKSIBU_URL', plugin_dir_url(__FILE__));

// Core: assets + admin menu
require_once WEBKSIBU_DIR . 'admin/core.php';

// Addons (shortcodes)
require_once WEBKSIBU_DIR . 'includes/webksibu-slider1.php';
require_once WEBKSIBU_DIR . 'includes/webksibu-slider2.php';
require_once WEBKSIBU_DIR . 'includes/webksibu-team1.php';
require_once WEBKSIBU_DIR . 'includes/webksibu-map1.php';
require_once WEBKSIBU_DIR . 'includes/webksibu-success1.php';
require_once WEBKSIBU_DIR . 'includes/webksibu-package1.php';



// Frontend preview handler
add_action('template_redirect', function () {

    // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only preview query var; no state change.
    if ( ! isset($_GET['webksibu_preview']) ) {
        return;
    }

    // Only admins should preview inside wp-admin iframe
    if ( ! current_user_can('manage_options') ) {
        status_header(403);
        exit;
    }

    // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only preview query var; no state change.
    $raw = sanitize_text_field( wp_unslash($_GET['webksibu_preview']) );

    /**
     * Security: whitelist ONLY your plugin shortcodes
     * We accept either:
     *  - webksibu_success1_3
     *  - [webksibu_success1_3]
     *  - webksibu_success1_3 cat="x"
     *  - [webksibu_success1_3 cat="x"]
     */
    $raw = trim($raw);

    // If they passed just "webksibu_xxx", wrap it
    if ( $raw !== '' && $raw[0] !== '[' ) {
        $raw = '[' . $raw . ']';
    }

    if ( ! preg_match('/^\[([a-z0-9_]+)(\s+[^\]]+)?\]$/i', $raw, $matches) ) {
        status_header(400);
        echo esc_html__('Invalid preview shortcode.', 'webkih-site-builder-kit');
        exit;
    }

    $allowed_shortcodes = [
        'webksibu_map1',
        'webksibu_slider1',
        'webksibu_slider2',
        'webksibu_team1',
        'webksibu_success1_3',
        'webksibu_success1_all',
        'webksibu_package1',
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
        wp_register_style('webksibu-preview-inline-style', false, [], WEBKSIBU_VER);
        wp_enqueue_style('webksibu-preview-inline-style');
        wp_add_inline_style('webksibu-preview-inline-style', 'body{margin:0;padding:18px;background:#fff;}img{max-width:100%;height:auto;}');
        wp_head();
        ?>
    </head>
    <body>
        <?php echo do_shortcode( $raw ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <?php wp_footer(); ?>
    </body>
    </html>
    <?php
    exit;
});




