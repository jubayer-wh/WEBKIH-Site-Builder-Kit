<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register slider shortcode.
 */
function cs_register_slider_shortcode() {
    add_shortcode( 'carousel_slider', 'cs_render_slider_shortcode' );
    add_shortcode( 'wbk_slider1', 'cs_render_slider_shortcode' ); // Backward compatible alias.
}
add_action( 'init', 'cs_register_slider_shortcode' );

/**
 * Render slider output.
 *
 * @param array<string, string> $atts Shortcode attributes.
 */
function cs_render_slider_shortcode( $atts ) {
    $defaults = cs_get_slider_settings();

    $atts = shortcode_atts(
        [
            'img1'   => $defaults['img1'],
            'img2'   => $defaults['img2'],
            'img3'   => $defaults['img3'],
            'height' => (string) $defaults['height'],
            'speed'  => (string) $defaults['speed'],
        ],
        $atts,
        'carousel_slider'
    );

    $height = max( 200, absint( $atts['height'] ) );
    $speed  = max( 1500, absint( $atts['speed'] ) );
    $images = array_values(
        array_filter(
            [
                esc_url_raw( $atts['img1'] ),
                esc_url_raw( $atts['img2'] ),
                esc_url_raw( $atts['img3'] ),
            ]
        )
    );

    if ( empty( $images ) ) {
        return '<p><strong>Carousel Slider:</strong> Add at least one image URL in shortcode or plugin settings.</p>';
    }

    wp_enqueue_style( 'cs-slider-css', CS_URL . 'assets/css/slider1.css', [], CS_VER );
    wp_enqueue_script( 'cs-slider-js', CS_URL . 'assets/js/slider1.js', [], CS_VER, true );

    $slider_id = 'cs-slider-' . wp_rand( 1000, 999999 );

    ob_start();
    ?>
    <div class="cs-slider1" id="<?php echo esc_attr( $slider_id ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>">
        <?php foreach ( $images as $index => $image ) : ?>
            <div class="cs-slide<?php echo 0 === $index ? ' is-active' : ''; ?>">
                <div class="cs-slide-bg" style="height:<?php echo esc_attr( $height ); ?>px;background-image:url('<?php echo esc_url( $image ); ?>');"></div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php

    return ob_get_clean();
}

/**
 * Read slider settings.
 *
 * @return array<string, mixed>
 */
function cs_get_slider_settings() {
    $defaults = [
        'img1'   => '',
        'img2'   => '',
        'img3'   => '',
        'height' => 360,
        'speed'  => 3500,
    ];

    $settings = get_option( 'cs_slider1_settings', [] );

    if ( ! is_array( $settings ) ) {
        return $defaults;
    }

    return wp_parse_args( $settings, $defaults );
}
