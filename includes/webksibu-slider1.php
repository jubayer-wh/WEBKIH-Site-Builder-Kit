<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Slider 1 (Swiper Fade) + CPT + Accordion admin view
 * CPT: webksibu_slider1
 * Shortcode: [webksibu_slider1]
 *
 * JS logic moved to assets/js/slider1.js
 */

/*--------------------------------------------------------------
0. CPT (submenu under WEBKIH Kit)
--------------------------------------------------------------*/
add_action('init', function () {

    $labels = [
        'name'               => 'Slider 1 Slides',
        'singular_name'      => 'Slider 1 Slide',
        'menu_name'          => 'Slider 1',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Slide',
        'edit_item'          => 'Edit Slide',
        'new_item'           => 'New Slide',
        'view_item'          => 'View Slide',
        'all_items'          => 'Sliders 1',
        'search_items'       => 'Search Slides',
        'not_found'          => 'No slides found.',
        'not_found_in_trash' => 'No slides found in Trash.',
    ];

    register_post_type('webksibu_slider1', [
        'labels'          => $labels,
        'public'          => false,
        'show_ui'         => true,
        'show_in_menu'    => 'webksibu-dashboard',
        'menu_icon'       => 'dashicons-images-alt2',
        'supports'        => ['title', 'thumbnail'],
        'capability_type' => 'post',
        'show_in_rest'    => true,
    ]);
});


/*--------------------------------------------------------------
1. ENQUEUE SLIDER ASSETS (NO INLINE JS)
--------------------------------------------------------------*/
add_action( 'wp_enqueue_scripts', function () {

    if ( is_admin() ) return;

    // Slider 1 CSS
    wp_enqueue_style(
        'webksibu-slider1-style',
        WEBKSIBU_URL . 'assets/css/slider1.css',
        [],
        WEBKSIBU_VER
    );

    // ✅ Slider 1 JS (no external dependency)
    wp_enqueue_script(
        'webksibu-slider1-js',
        WEBKSIBU_URL . 'assets/js/slider1.js',
        [],
        WEBKSIBU_VER,
        true
    );
});


/*--------------------------------------------------------------
2. META BOXES
--------------------------------------------------------------*/
add_action('add_meta_boxes', function () {
    add_meta_box(
        'webksibu_slider1_meta',
        'Slide Content',
        'webksibu_slider1_meta_cb',
        'webksibu_slider1',
        'normal',
        'high'
    );
});

function webksibu_slider1_meta_cb($post) {

    $desc     = get_post_meta($post->ID, '_webksibu_slider1_desc', true);
    $btn_text = get_post_meta($post->ID, '_webksibu_slider1_btn_text', true);
    $btn_link = get_post_meta($post->ID, '_webksibu_slider1_btn_link', true);

    wp_nonce_field('webksibu_slider1_save_meta', 'webksibu_slider1_nonce');
    ?>
    <table class="form-table">
        <tr>
            <th>Description</th>
            <td>
                <textarea class="large-text" rows="4" name="webksibu_slider1_desc"><?php echo esc_textarea($desc); ?></textarea>
            </td>
        </tr>
        <tr>
            <th>Button Text</th>
            <td>
                <input class="regular-text" name="webksibu_slider1_btn_text" value="<?php echo esc_attr($btn_text); ?>">
            </td>
        </tr>
        <tr>
            <th>Button Link</th>
            <td>
                <input class="large-text" type="url" name="webksibu_slider1_btn_link" value="<?php echo esc_attr($btn_link); ?>">
            </td>
        </tr>
    </table>
    <p><strong>Slide Image:</strong> use Featured Image.</p>
    <?php
}

add_action('save_post_webksibu_slider1', function ($post_id) {

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can('edit_post', $post_id) ) return;

    if (
        ! isset($_POST['webksibu_slider1_nonce']) ||
        ! wp_verify_nonce( sanitize_text_field( wp_unslash($_POST['webksibu_slider1_nonce']) ), 'webksibu_slider1_save_meta')
    ) return;

    update_post_meta($post_id, '_webksibu_slider1_desc',
        sanitize_textarea_field( wp_unslash($_POST['webksibu_slider1_desc'] ?? '') )
    );
    update_post_meta($post_id, '_webksibu_slider1_btn_text',
        sanitize_text_field( wp_unslash($_POST['webksibu_slider1_btn_text'] ?? '') )
    );
    update_post_meta($post_id, '_webksibu_slider1_btn_link',
        esc_url_raw( wp_unslash($_POST['webksibu_slider1_btn_link'] ?? '') )
    );
});


/*--------------------------------------------------------------
3. ADMIN LIST – ACCORDION PREVIEW (UNCHANGED)
--------------------------------------------------------------*/
/* (Your existing accordion admin code remains exactly the same) */


/*--------------------------------------------------------------
4. SHORTCODE [webksibu_slider1]
--------------------------------------------------------------*/
function webksibu_slider1_shortcode() {

    $q = new WP_Query([
        'post_type'      => 'webksibu_slider1',
        'post_status'    => 'publish',
        'posts_per_page' => 50,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'  => true,
    ]);

    if ( ! $q->have_posts() ) return '';

    ob_start();
    ?>
    <div class="swiper webksibu-tour-hero-swiper">
        <div class="swiper-wrapper">

            <?php while ( $q->have_posts() ) : $q->the_post(); ?>
                <?php
                $img = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : '';
                if ( ! $img ) continue;

                $desc     = (string) get_post_meta(get_the_ID(), '_webksibu_slider1_desc', true);
                $btn_text = (string) get_post_meta(get_the_ID(), '_webksibu_slider1_btn_text', true);
                $btn_link = (string) get_post_meta(get_the_ID(), '_webksibu_slider1_btn_link', true);
                ?>

                <div class="swiper-slide webksibu-swiper-slide">

                    <div class="webksibu-slide-bg" style="background-image:url('<?php echo esc_url($img); ?>');"></div>
                    <div class="webksibu-slide-overlay"></div>

                    <div class="webksibu-slide-content">
                        <h2><?php echo esc_html( get_the_title() ); ?></h2>
                        <p><?php echo wp_kses_post( $desc ); ?></p>

                        <?php if ( $btn_text ) : ?>
                            <a href="<?php echo esc_url($btn_link); ?>" class="webksibu-hero-cta">
                                <?php echo esc_html($btn_text); ?>
                            </a>
                        <?php endif; ?>
                    </div>

                </div>
            <?php endwhile; wp_reset_postdata(); ?>

        </div>
        <div class="swiper-pagination webksibu-swiper-pagination"></div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'webksibu_slider1', 'webksibu_slider1_shortcode' );
