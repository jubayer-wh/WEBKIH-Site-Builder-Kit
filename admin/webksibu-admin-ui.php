<?php
if ( ! defined('ABSPATH') ) exit;
if ( ! current_user_can('manage_options') ) return;

require WEBKSIBU_DIR . 'admin/modules/settings-dashboard.php';
