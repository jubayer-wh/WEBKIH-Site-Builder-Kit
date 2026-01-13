<?php
/**
 * Plugin Name: WEBKIH Site Builder Kit
 * Description: Modular website sections (Hero, Slider, Team, Packages, Contact, Loader) with shortcodes + admin gallery.
 * Version: 1.0.0
 * Author: WEBKIH
 * License: GPLv2 or later
 * Text Domain: webkih-site-builder-kit
 */

if ( ! defined('ABSPATH') ) exit;

define('WBK_VER', '1.0.0');
define('WBK_DIR', plugin_dir_path(__FILE__));
define('WBK_URL', plugin_dir_url(__FILE__));

// Core: assets + admin menu
require_once WBK_DIR . 'admin/core.php';

// Addons (shortcodes)
require_once WBK_DIR . 'includes/wbk-hero1.php';
require_once WBK_DIR . 'includes/wbk-slider1.php';
require_once WBK_DIR . 'includes/wbk-team1.php';
require_once WBK_DIR . 'includes/wbk-package1.php';
require_once WBK_DIR . 'includes/wbk-loader1.php';
require_once WBK_DIR . 'includes/wbk-map1.php';

