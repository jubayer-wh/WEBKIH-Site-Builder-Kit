<?php
if ( ! defined('WP_UNINSTALL_PLUGIN') ) exit;

// Delete plugin options
delete_option('wbk_settings');
