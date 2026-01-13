<?php
if ( ! defined('ABSPATH') ) exit;

add_shortcode('wbk_map1', function () {

    // If you moved CSS to a file, enqueue here:
    // Example: assets/css/contact.css OR create assets/css/map1.css
    // wp_enqueue_style('wbk-map1-css', WBK_URL . 'assets/css/map1.css', [], WBK_VER);

    $defaults = [
        'title'       => 'Visit WEBKIH',
        'address'     => 'Brudger Bazaar Rd, Atpara 2470',
        'email'       => 'jubayer@webkih.com',
        'hours'       => 'Sat - Thu | 9 AM - 8 PM',
        'button_text' => 'Get Directions',
        'button_url'  => 'https://maps.app.goo.gl/gNeAjCxHXWLsrKwE6',
        'iframe_src'  => '',
    ];

    $settings = get_option('wbk_map1_settings', []);
    $s = array_merge($defaults, is_array($settings) ? $settings : []);

    ob_start(); ?>
    <div class="wbk-map-section">
        <div class="wbk-map-container">
            <div class="wbk-map-card">

                <div class="wbk-map-info">
                    <h2><?php echo esc_html($s['title']); ?></h2>

                    <div class="wbk-details">
                        <div class="wbk-row">
                            <span>📍</span>
                            <span><?php echo esc_html($s['address']); ?></span>
                        </div>
                        <div class="wbk-row">
                            <span>📧</span>
                            <span><?php echo esc_html($s['email']); ?></span>
                        </div>
                        <div class="wbk-row">
                            <span>🕒</span>
                            <span><?php echo esc_html($s['hours']); ?></span>
                        </div>
                    </div>

                    <a href="<?php echo esc_url($s['button_url']); ?>"
                       target="_blank"
                       class="wbk-map-btn" rel="noopener">
                        <?php echo esc_html($s['button_text']); ?>
                    </a>
                </div>

                <div class="wbk-map-iframe">
                    <?php if ( ! empty($s['iframe_src']) ) : ?>
                        <iframe
                            src="<?php echo esc_url($s['iframe_src']); ?>"
                            allowfullscreen
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    <?php else : ?>
                        <p style="padding:20px;color:#fff;">Map iframe src is not set yet.</p>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
});
