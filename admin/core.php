<?php
if ( ! defined('ABSPATH') ) exit;

/**
 * Enqueue admin + frontend assets (WP-standard)
 */
add_action('wp_enqueue_scripts', function () {

    // Frontend base JS (optional, used by sliders etc.)
    wp_enqueue_script(
        'wbk-main-js',
        WBK_URL . 'assets/js/main.js',
        [],
        WBK_VER,
        true
    );

    // Optional: Map1 CSS (only if you want it globally).
    // Better approach: enqueue inside shortcode/module, but keeping your behavior.
    wp_enqueue_style(
        'wbk-map1-css',
        WBK_URL . 'assets/css/map1.css',
        [],
        WBK_VER
    );
});

add_action('admin_enqueue_scripts', function ($hook) {

    // Admin assets only on WBK pages
    if ( strpos($hook, 'wbk') === false ) {
        return;
    }

    wp_enqueue_style(
        'wbk-admin-css',
        WBK_URL . 'assets/css/admin.css',
        [],
        WBK_VER
    );

    wp_enqueue_script(
        'wbk-admin-js',
        WBK_URL . 'assets/js/main.js',
        ['jquery'],
        WBK_VER,
        true
    );
});


/**
 * Admin Menu
 */
add_action('admin_menu', function () {

    add_menu_page(
        'WEBKIH Site Builder Kit',
        'WEBKIH Kit',
        'manage_options',
        'wbk-dashboard',
        'wbk_render_dashboard',
        'dashicons-screenoptions',
        26
    );

    /**
     * ✅ Single Settings Hub (all module settings inside)
     * URL: admin.php?page=wbk-settings
     */

    add_submenu_page(
        'wbk-dashboard',
        'Map (Style 1)',
        'Map (Style 1)',
        'manage_options',
        'wbk-map1',
        'wbk_render_map1_admin_page'
    );


    add_submenu_page(
        'wbk-dashboard',
        'WEBKIH Kit Settings',
        'Settings',
        'manage_options',
        'wbk-settings',
        'wbk_render_settings_hub'
    );

    add_submenu_page(
        'wbk-dashboard',
        'Documentation',
        'Documentation',
        'manage_options',
        'wbk-documentation',
        'wbk_render_docs'
    );

    /**
     * ❌ Removed old individual settings pages:
     * - wbk-map1
     * - wbk-success1-settings
     * Because now everything goes into the Settings Hub.
     */

});


/**
 * Page renders
 */
function wbk_render_dashboard() {
    require WBK_DIR . 'admin/wbk-admin-ui.php';
}

function wbk_render_settings_hub() {
    // This is your NEW single settings UI (cards/modules)
    require WBK_DIR . 'admin/settings-hub.php';
}

function wbk_render_docs() {
    require WBK_DIR . 'admin/wbk-documentation.php';
}

function wbk_render_map1_settings() {
    require WBK_DIR . 'includes/wbk-map1.php';
}
