<?php
if ( ! defined('ABSPATH') ) exit;

add_shortcode('wbk_package1', function(){

    wp_enqueue_style('wbk-packages-css', WBK_URL . 'assets/css/packages.css', [], WBK_VER);

    // Default demo packages (later replace with CPT + single page)
    $packs = [
        ['title' => 'Starter', 'price' => '$99', 'features' => ['1 Page', 'Basic SEO', 'Contact Form']],
        ['title' => 'Business', 'price' => '$199', 'features' => ['5 Pages', 'Better SEO', 'Speed Setup']],
        ['title' => 'Premium', 'price' => '$399', 'features' => ['10 Pages', 'Advanced SEO', 'Priority Support']],
    ];

    ob_start(); ?>
    <section class="wbk-packages1">
        <div class="wbk-pack-grid">
            <?php foreach($packs as $p): ?>
                <div class="wbk-pack">
                    <h3 style="margin:0;"><?php echo esc_html($p['title']); ?></h3>
                    <div class="wbk-price"><?php echo esc_html($p['price']); ?></div>
                    <ul style="margin:0;padding-left:18px;">
                        <?php foreach($p['features'] as $f): ?>
                            <li><?php echo esc_html($f); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
        <p style="margin-top:12px;opacity:.75;">
            Note: Later you can convert this to CPT: <code>wbk_package</code> with single pages.
        </p>
    </section>
    <?php
    return ob_get_clean();
});
