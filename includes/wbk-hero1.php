<?php
if ( ! defined('ABSPATH') ) exit;

add_shortcode('wbk_hero1', function($atts){

    wp_enqueue_style('wbk-hero-css', WBK_URL . 'assets/css/hero.css', [], WBK_VER);

    $a = shortcode_atts([
        'title' => 'Build a modern website with WEBKIH',
        'subtitle' => 'Fast sections, clean design, easy updates from WordPress.',
        'button' => 'Contact',
        'url' => home_url('/contact'),
    ], $atts);

    ob_start(); ?>
    <section class="wbk-hero1">
        <h1><?php echo esc_html($a['title']); ?></h1>
        <p><?php echo esc_html($a['subtitle']); ?></p>
        <a href="<?php echo esc_url($a['url']); ?>"><?php echo esc_html($a['button']); ?></a>
    </section>
    <?php
    return ob_get_clean();
});
