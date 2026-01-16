<?php
if ( ! defined('ABSPATH') ) exit;

/**
 * ADMIN ASSETS
 */
add_action('admin_enqueue_scripts', function ($hook) {

    // Only load on WBK Documentation page
    $page = isset($_GET['page']) ? sanitize_key( wp_unslash($_GET['page']) ) : '';

    if ( $page !== 'wbk-documentation' ) {
        return;
    }

    wp_enqueue_style(
        'wbk-documentation-css',
        WBK_URL . 'assets/css/documentation.css',
        [],
        WBK_VER
    );

    wp_enqueue_script(
        'wbk-documentation-js',
        WBK_URL . 'assets/js/documentation.js',
        [],
        WBK_VER,
        true
    );

    // Pass data to documentation.js (no inline script needed)
    wp_localize_script('wbk-documentation-js', 'WBK_DOCS', [
        'previewBase' => home_url('/'),
        'nonce'       => wp_create_nonce('wbk_preview_shortcode'),
    ]);
});



/*--------------------------------------------------------------
2) ADMIN MENU
--------------------------------------------------------------*/
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
});


/*--------------------------------------------------------------
3) PAGE RENDERERS
--------------------------------------------------------------*/
function wbk_render_dashboard() {
    require WBK_DIR . 'admin/wbk-admin-ui.php';
}

function wbk_render_settings_hub() {
    require WBK_DIR . 'admin/settings-hub.php';
}

function wbk_render_docs() {
    require WBK_DIR . 'admin/wbk-documentation.php';
}
