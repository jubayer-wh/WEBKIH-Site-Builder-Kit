<?php
if ( ! defined('ABSPATH') ) exit;

/**
 * Enqueue admin + frontend assets (WP-standard)
 */
function webksibu_enqueue_frontend_assets() {

    wp_enqueue_script(
        'webksibu-main-js',
        WEBKSIBU_URL . 'assets/js/main.js',
        [],
        WEBKSIBU_VER,
        true
    );

    wp_enqueue_style(
        'webksibu-map1-css',
        WEBKSIBU_URL . 'assets/css/map1.css',
        [],
        WEBKSIBU_VER
    );
}
add_action('wp_enqueue_scripts', 'webksibu_enqueue_frontend_assets');

function webksibu_enqueue_admin_assets( $hook ) {

    if ( strpos($hook, 'webksibu') === false ) {
        return;
    }

    wp_enqueue_style(
        'webksibu-admin-css',
        WEBKSIBU_URL . 'assets/css/admin.css',
        [],
        WEBKSIBU_VER
    );

    wp_enqueue_script(
        'webksibu-admin-js',
        WEBKSIBU_URL . 'assets/js/main.js',
        ['jquery'],
        WEBKSIBU_VER,
        true
    );

    if ( 'toplevel_page_webksibu-dashboard' === $hook ) {
        webksibu_enqueue_settings_dashboard_assets();
    }

    if ( strpos($hook, 'webksibu-settings') !== false ) {
        wp_enqueue_style(
            'webksibu-settings-hub-css',
            WEBKSIBU_URL . 'assets/css/settings-hub.css',
            ['webksibu-admin-css'],
            WEBKSIBU_VER
        );

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only module router in admin.
        $module = isset($_GET['module']) ? sanitize_key( wp_unslash($_GET['module']) ) : 'success1';
        if ( 'dashboard' === $module ) {
            webksibu_enqueue_settings_dashboard_assets();
        }
    }

    if ( strpos($hook, 'webksibu-documentation') !== false ) {
        wp_enqueue_style(
            'webksibu-documentation-css',
            WEBKSIBU_URL . 'assets/css/documentation.css',
            ['webksibu-admin-css'],
            WEBKSIBU_VER
        );
        wp_enqueue_script(
            'webksibu-documentation-js',
            WEBKSIBU_URL . 'assets/js/documentation.js',
            [],
            WEBKSIBU_VER,
            true
        );
    }
}
add_action('admin_enqueue_scripts', 'webksibu_enqueue_admin_assets');

function webksibu_enqueue_settings_dashboard_assets() {
    $defaults = [
        'primary_color' => '#0e304c',
        'accent_color'  => '#38bdf8',
    ];

    $settings = get_option('webksibu_settings', []);
    $settings = array_merge($defaults, is_array($settings) ? $settings : []);

    $primary = sanitize_hex_color($settings['primary_color']) ?: $defaults['primary_color'];
    $accent  = sanitize_hex_color($settings['accent_color']) ?: $defaults['accent_color'];

    wp_enqueue_style(
        'webksibu-settings-dashboard-css',
        WEBKSIBU_URL . 'assets/css/settings-dashboard.css',
        ['webksibu-admin-css'],
        WEBKSIBU_VER
    );

    wp_add_inline_style(
        'webksibu-settings-dashboard-css',
        ':root{--webksibu-primary:' . $primary . ';--webksibu-accent:' . $accent . ';--webksibu-border:#e5e7eb;--webksibu-muted:#64748b;--webksibu-card:#ffffff;--webksibu-bg:#f8fafc;}'
    );

    wp_enqueue_script(
        'webksibu-settings-dashboard-js',
        WEBKSIBU_URL . 'assets/js/settings-dashboard.js',
        [],
        WEBKSIBU_VER,
        true
    );
}


/**
 * Admin Menu
 */
function webksibu_register_admin_menu() {

    add_menu_page(
        'WEBKIH Site Builder Kit',
        'WEBKIH Kit',
        'manage_options',
        'webksibu-dashboard',
        'webksibu_render_dashboard',
        'dashicons-screenoptions',
        26
    );

    add_submenu_page(
        'webksibu-dashboard',
        'Map (Style 1)',
        'Map (Style 1)',
        'manage_options',
        'webksibu-map1',
        'webksibu_render_map1_admin_page'
    );


    add_submenu_page(
        'webksibu-dashboard',
        'WEBKIH Kit Settings',
        'Settings',
        'manage_options',
        'webksibu-settings',
        'webksibu_render_settings_hub'
    );

    add_submenu_page(
        'webksibu-dashboard',
        'Documentation',
        'Documentation',
        'manage_options',
        'webksibu-documentation',
        'webksibu_render_docs'
    );
}
add_action('admin_menu', 'webksibu_register_admin_menu');


/**
 * Page renders
 */
function webksibu_render_dashboard() {
    require WEBKSIBU_DIR . 'admin/webksibu-admin-ui.php';
}

function webksibu_render_settings_hub() {
    require WEBKSIBU_DIR . 'admin/settings-hub.php';
}

function webksibu_render_docs() {
    require WEBKSIBU_DIR . 'admin/webksibu-documentation.php';
}

function webksibu_render_map1_settings() {
    require WEBKSIBU_DIR . 'includes/webksibu-map1.php';
}
