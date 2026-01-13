<?php
if ( ! defined('ABSPATH') ) exit;

/**
 * Enqueue admin + frontend assets
 */
function wbk_enqueue_assets() {

    // Frontend base JS (optional, used by slider)
    add_action('wp_enqueue_scripts', function(){
        wp_enqueue_script('wbk-main-js', WBK_URL . 'assets/js/main.js', [], WBK_VER, true);
    });

    // Admin assets only on WBK pages
    add_action('admin_enqueue_scripts', function($hook){
        if ( strpos($hook, 'wbk') === false ) return;

        wp_enqueue_style('wbk-admin-css', WBK_URL . 'assets/css/admin.css', [], WBK_VER);
        wp_enqueue_script('wbk-admin-js', WBK_URL . 'assets/js/main.js', ['jquery'], WBK_VER, true);
    });

    // map1 CSS (optional)
    add_action('wp_enqueue_scripts', function(){
        wp_enqueue_style('wbk-map1-css', WBK_URL . 'assets/css/map1.css', [], WBK_VER);
    });

}
wbk_enqueue_assets();

/**
 * Admin Menu
 */
add_action('admin_menu', function(){

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
        'Settings',
        'Settings',
        'manage_options',
        'wbk-settings',
        'wbk_render_settings'
    );

    add_submenu_page(
        'wbk-dashboard',
        'Documentation',
        'Documentation',
        'manage_options',
        'wbk-documentation',
        'wbk_render_docs'
    );

    add_submenu_page(
    'wbk-dashboard',
    'Map (Style 1)',
    'Map (Style 1)',
    'manage_options',
    'wbk-map1',
    'wbk_render_map1_settings'
);

});

/**
 * Page renders
 */
function wbk_render_dashboard() {
    require WBK_DIR . 'admin/wbk-admin-ui.php';
}

function wbk_render_settings() {
    require WBK_DIR . 'admin/settings-dashboard.php';
}

function wbk_render_docs() {
    require WBK_DIR . 'admin/wbk-documentation.php';
}

function wbk_render_map1_settings() {
    require WBK_DIR . 'admin/wbk-map1-settings.php';
}
