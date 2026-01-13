<?php
if ( ! defined('ABSPATH') ) exit;

add_shortcode('wbk_loader1', function($atts){

    wp_enqueue_style('wbk-loader-css', WBK_URL . 'assets/css/loader.css', [], WBK_VER);

    $a = shortcode_atts([
        'text' => 'Loading...',
    ], $atts);

    ob_start(); ?>
    <div class="wbk-loader1" role="status" aria-live="polite">
        <span class="dot"></span><span class="dot"></span><span class="dot"></span>
        <span><?php echo esc_html($a['text']); ?></span>
    </div>
    <?php
    return ob_get_clean();
});
