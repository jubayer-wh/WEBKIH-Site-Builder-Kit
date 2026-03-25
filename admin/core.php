<?php
if ( ! defined('ABSPATH') ) exit;

/**
 * Enqueue admin + frontend assets (WP-standard)
 */
function wbk_enqueue_frontend_assets() {

    wp_enqueue_script(
        'wbk-main-js',
        WBK_URL . 'assets/js/main.js',
        [],
        WBK_VER,
        true
    );

    wp_enqueue_style(
        'wbk-map1-css',
        WBK_URL . 'assets/css/map1.css',
        [],
        WBK_VER
    );
}
add_action('wp_enqueue_scripts', 'wbk_enqueue_frontend_assets');

function wbk_enqueue_admin_assets( $hook ) {

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

    if ( 'toplevel_page_wbk-dashboard' === $hook ) {
        wbk_enqueue_settings_dashboard_assets();
    }

    if ( strpos($hook, 'wbk-settings') !== false ) {
        wp_enqueue_style(
            'wbk-settings-hub-css',
            WBK_URL . 'assets/css/settings-hub.css',
            ['wbk-admin-css'],
            WBK_VER
        );

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only module router in admin.
        $module = isset($_GET['module']) ? sanitize_key( wp_unslash($_GET['module']) ) : 'success1';
        if ( 'dashboard' === $module ) {
            wbk_enqueue_settings_dashboard_assets();
        }
    }

    if ( strpos($hook, 'wbk-documentation') !== false ) {
        wp_enqueue_style(
            'wbk-documentation-css',
            WBK_URL . 'assets/css/documentation.css',
            ['wbk-admin-css'],
            WBK_VER
        );
        wp_enqueue_script(
            'wbk-documentation-js',
            WBK_URL . 'assets/js/documentation.js',
            [],
            WBK_VER,
            true
        );
    }
}
add_action('admin_enqueue_scripts', 'wbk_enqueue_admin_assets');

function wbk_enqueue_settings_dashboard_assets() {
    $defaults = [
        'primary_color' => '#0e304c',
        'accent_color'  => '#38bdf8',
    ];

    $settings = get_option('wbk_settings', []);
    $settings = array_merge($defaults, is_array($settings) ? $settings : []);

    $primary = sanitize_hex_color($settings['primary_color']) ?: $defaults['primary_color'];
    $accent  = sanitize_hex_color($settings['accent_color']) ?: $defaults['accent_color'];

    wp_enqueue_style(
        'wbk-settings-dashboard-css',
        WBK_URL . 'assets/css/settings-dashboard.css',
        ['wbk-admin-css'],
        WBK_VER
    );

    wp_add_inline_style(
        'wbk-settings-dashboard-css',
        ':root{--wbk-primary:' . $primary . ';--wbk-accent:' . $accent . ';--wbk-border:#e5e7eb;--wbk-muted:#64748b;--wbk-card:#ffffff;--wbk-bg:#f8fafc;}'
    );

    wp_enqueue_script(
        'wbk-settings-dashboard-js',
        WBK_URL . 'assets/js/settings-dashboard.js',
        [],
        WBK_VER,
        true
    );
}


/**
 * Admin Menu
 */
function wbk_register_admin_menu() {

    add_menu_page(
        'WEBKIH Site Builder Kit',
        'WEBKIH Kit',
        'manage_options',
        'wbk-dashboard',
        'wbk_render_dashboard',
        'dashicons-screenoptions',
        26
    );

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
}
add_action('admin_menu', 'wbk_register_admin_menu');


/**
 * Page renders
 */
function wbk_render_dashboard() {
    require WBK_DIR . 'admin/wbk-admin-ui.php';
}

function wbk_render_settings_hub() {
    require WBK_DIR . 'admin/settings-hub.php';
}

function wbk_render_docs() {
    require WBK_DIR . 'admin/wbk-documentation.php';
}

function wbk_render_map1_settings() {
    require WBK_DIR . 'includes/wbk-map1.php';
}
