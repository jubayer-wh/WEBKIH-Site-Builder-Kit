<?php
if ( ! defined('ABSPATH') ) exit;

add_shortcode('wbk_slider1', function($atts){

    // Uses main.js rotation logic
    $a = shortcode_atts([
        'img1' => '',
        'img2' => '',
        'img3' => '',
        'height' => '360',
    ], $atts);

    // Basic inline CSS to keep file count low (you can create slider.css later)
    $height = max(200, (int)$a['height']);

    $imgs = array_filter([$a['img1'], $a['img2'], $a['img3']]);

    if ( empty($imgs) ) {
        return '<p><strong>WBK Slider:</strong> Please add img1, img2, img3 URLs.</p>';
    }

    ob_start(); ?>
    <div class="wbk-slider1" style="max-width:1100px;margin:22px auto;border-radius:18px;overflow:hidden;">
        <?php foreach($imgs as $img): ?>
            <div class="wbk-slide" style="display:none;">
                <div style="height:<?php echo esc_attr($height); ?>px;background:url('<?php echo esc_url($img); ?>') center/cover no-repeat;"></div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
});
