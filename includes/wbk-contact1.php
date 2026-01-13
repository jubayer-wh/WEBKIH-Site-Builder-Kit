<?php
if ( ! defined('ABSPATH') ) exit;

add_shortcode('wbk_contact1', function($atts){

    wp_enqueue_style('wbk-contact-css', WBK_URL . 'assets/css/contact.css', [], WBK_VER);

    $a = shortcode_atts([
        'email' => 'jubayer@webkih.com',
        'phone' => '+880 0000 000000',
        'address' => 'Dhaka, Bangladesh',
        'form_shortcode' => '', // e.g. [contact-form-7 id="123"]
    ], $atts);

    ob_start(); ?>
    <section class="wbk-contact1">
        <h3>Contact</h3>

        <div class="wbk-contact-grid">
            <div class="wbk-box">
                <strong>Email</strong>
                <p style="margin:6px 0 0;"><?php echo esc_html($a['email']); ?></p>
            </div>

            <div class="wbk-box">
                <strong>Phone</strong>
                <p style="margin:6px 0 0;"><?php echo esc_html($a['phone']); ?></p>
            </div>

            <div class="wbk-box">
                <strong>Address</strong>
                <p style="margin:6px 0 0;"><?php echo esc_html($a['address']); ?></p>
            </div>
        </div>

        <?php if ( ! empty($a['form_shortcode']) ): ?>
            <div class="wbk-box" style="margin-top:14px;">
                <?php echo do_shortcode( $a['form_shortcode'] ); ?>
            </div>
        <?php endif; ?>
    </section>
    <?php
    return ob_get_clean();
});
