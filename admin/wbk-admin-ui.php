<?php
if ( ! defined('ABSPATH') ) exit;
if ( ! current_user_can('manage_options') ) return;

require WBK_DIR . 'admin/modules/settings-dashboard.php';
