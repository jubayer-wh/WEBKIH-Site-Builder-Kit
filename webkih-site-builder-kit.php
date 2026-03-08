<?php
/**
 * Plugin Name: Carousel Slider
 * Description: Lightweight WordPress slider plugin extracted from slider1 module with shortcode and admin settings.
 * Version: 1.0.0
 * Author: WEBKIH
 * License: GPLv2 or later
 * Text Domain: carousel-slider
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'CS_VER', '1.0.0' );
define( 'CS_DIR', plugin_dir_path( __FILE__ ) );
define( 'CS_URL', plugin_dir_url( __FILE__ ) );

require_once CS_DIR . 'includes/slider1.php';
require_once CS_DIR . 'admin/settings-page.php';
