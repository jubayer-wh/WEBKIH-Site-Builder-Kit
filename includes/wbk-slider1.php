<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Slider 1 (Swiper Fade) + CPT + Accordion admin view
 * CPT: wbk_slider1
 * Shortcode: [wbk_slider1]
 *
 * Notes:
 * - Slides are managed from WP Dashboard under WEBKIH Kit (submenu)
 * - Uses Featured Image for slide image
 * - Uses meta fields for: desc, btn_text, btn_link
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
        'all_items'          => 'All Slides',
        'search_items'       => 'Search Slides',
        'not_found'          => 'No slides found.',
        'not_found_in_trash' => 'No slides found in Trash.',
    ];

    register_post_type('wbk_slider1', [
        'labels'          => $labels,
        'public'          => false,
        'show_ui'         => true,
        'show_in_menu'    => 'wbk-dashboard', // ✅ under WEBKIH Kit
        'menu_icon'       => 'dashicons-images-alt2',
        'supports'        => ['title', 'thumbnail'],
        'capability_type' => 'post',
        'show_in_rest'    => true,
    ]);
});


/*--------------------------------------------------------------
1. ENQUEUE SLIDER ASSETS
--------------------------------------------------------------*/
function wbk_slider1_assets() {

    if ( is_admin() ) return;

    // Swiper CSS
    wp_enqueue_style(
        'swiper-css',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
        array(),
        '11.0'
    );

    // Your custom slider styles (moved to slider1.css)
    wp_enqueue_style(
        'wbk-slider1-style',
        plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/slider1.css',
        array('swiper-css'),
        '1.0'
    );

    // Swiper JS
    wp_enqueue_script(
        'swiper-js',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
        array(),
        '11.0',
        true
    );

    // Slider Init Script (INLINE, logic unchanged)
    wp_add_inline_script(
        'swiper-js',
        "
        const wbkTourSwiper = new Swiper('.wbk-tour-hero-swiper', {
            loop: true,
            speed: 1000,
            effect: 'fade',
            fadeEffect: { crossFade: true },
            autoplay: {
                delay: 6000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.wbk-swiper-pagination',
                clickable: true,
            },
        });
        "
    );
}
add_action( 'wp_enqueue_scripts', 'wbk_slider1_assets' );


/*--------------------------------------------------------------
2. META BOXES (desc, button text, button link)
--------------------------------------------------------------*/
add_action('add_meta_boxes', function () {
    add_meta_box(
        'wbk_slider1_meta',
        'Slide Content',
        'wbk_slider1_meta_cb',
        'wbk_slider1',
        'normal',
        'high'
    );
});

function wbk_slider1_meta_cb($post) {

    $desc     = get_post_meta($post->ID, '_wbk_slider1_desc', true);
    $btn_text = get_post_meta($post->ID, '_wbk_slider1_btn_text', true);
    $btn_link = get_post_meta($post->ID, '_wbk_slider1_btn_link', true);

    wp_nonce_field('wbk_slider1_save_meta', 'wbk_slider1_nonce');
    ?>
    <table class="form-table">
        <tr>
            <th><label for="wbk_slider1_desc">Description</label></th>
            <td>
                <textarea class="large-text" rows="4" id="wbk_slider1_desc" name="wbk_slider1_desc"><?php echo esc_textarea($desc); ?></textarea>
            </td>
        </tr>
        <tr>
            <th><label for="wbk_slider1_btn_text">Button Text</label></th>
            <td>
                <input type="text" class="regular-text" id="wbk_slider1_btn_text" name="wbk_slider1_btn_text" value="<?php echo esc_attr($btn_text); ?>">
            </td>
        </tr>
        <tr>
            <th><label for="wbk_slider1_btn_link">Button Link</label></th>
            <td>
                <input type="url" class="large-text" id="wbk_slider1_btn_link" name="wbk_slider1_btn_link" value="<?php echo esc_attr($btn_link); ?>" placeholder="https://">
            </td>
        </tr>
    </table>
    <p><strong>Slide Image:</strong> use <em>Featured Image</em>.</p>
    <?php
}

add_action('save_post_wbk_slider1', function ($post_id) {

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can('edit_post', $post_id) ) return;

    if (
        ! isset($_POST['wbk_slider1_nonce']) ||
        ! wp_verify_nonce($_POST['wbk_slider1_nonce'], 'wbk_slider1_save_meta')
    ) {
        return;
    }

    $desc     = isset($_POST['wbk_slider1_desc']) ? sanitize_textarea_field( wp_unslash($_POST['wbk_slider1_desc']) ) : '';
    $btn_text = isset($_POST['wbk_slider1_btn_text']) ? sanitize_text_field( wp_unslash($_POST['wbk_slider1_btn_text']) ) : '';
    $btn_link = isset($_POST['wbk_slider1_btn_link']) ? esc_url_raw( wp_unslash($_POST['wbk_slider1_btn_link']) ) : '';

    update_post_meta($post_id, '_wbk_slider1_desc', $desc);
    update_post_meta($post_id, '_wbk_slider1_btn_text', $btn_text);
    update_post_meta($post_id, '_wbk_slider1_btn_link', $btn_link);
});


/*--------------------------------------------------------------
3. ADMIN LIST VIEW AS ACCORDION (compact preview)
--------------------------------------------------------------*/
add_filter('manage_wbk_slider1_posts_columns', function($cols){
    $cols = [];
    $cols['cb'] = '<input type="checkbox" />';
    $cols['title'] = 'Slide Title';
    $cols['wbk_preview'] = 'Accordion Preview';
    $cols['date'] = 'Date';
    return $cols;
});

add_action('manage_wbk_slider1_posts_custom_column', function($col, $post_id){

    if ( $col !== 'wbk_preview' ) return;

    $img = has_post_thumbnail($post_id) ? get_the_post_thumbnail_url($post_id, 'thumbnail') : '';
    $desc = (string) get_post_meta($post_id, '_wbk_slider1_desc', true);
    $btn_text = (string) get_post_meta($post_id, '_wbk_slider1_btn_text', true);
    $btn_link = (string) get_post_meta($post_id, '_wbk_slider1_btn_link', true);

    $desc = wp_trim_words($desc, 22, '…');

    ?>
    <div class="wbk-acc">
        <button type="button" class="wbk-acc__head" aria-expanded="false">
            <span class="wbk-acc__label">View details</span>
            <span class="wbk-acc__chev">▾</span>
        </button>

        <div class="wbk-acc__panel" hidden>
            <div class="wbk-acc__row">
                <?php if ( $img ) : ?>
                    <img class="wbk-acc__thumb" src="<?php echo esc_url($img); ?>" alt="">
                <?php endif; ?>

                <div class="wbk-acc__meta">
                    <?php if ( $desc !== '' ) : ?>
                        <div><strong>Description:</strong> <?php echo esc_html($desc); ?></div>
                    <?php endif; ?>

                    <?php if ( $btn_text !== '' ) : ?>
                        <div><strong>Button:</strong> <?php echo esc_html($btn_text); ?></div>
                    <?php endif; ?>

                    <?php if ( $btn_link !== '' ) : ?>
                        <div><strong>Link:</strong> <?php echo esc_html($btn_link); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php

}, 10, 2);

add_action('admin_enqueue_scripts', function($hook){

    // Only load on Slider 1 CPT list screen
    if ( $hook !== 'edit.php' ) return;

    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if ( ! $screen || $screen->post_type !== 'wbk_slider1' ) return;

    $css = "
    .wbk-acc{max-width:520px}
    .wbk-acc__head{
        width:100%;
        display:flex;
        justify-content:space-between;
        align-items:center;
        padding:8px 10px;
        border-radius:10px;
        border:1px solid #e5e7eb;
        background:#fff;
        cursor:pointer;
        font-weight:700;
    }
    .wbk-acc__head:focus{outline:none; box-shadow:0 0 0 3px rgba(56,189,248,.25)}
    .wbk-acc__panel{
        margin-top:8px;
        padding:10px;
        border:1px solid #eef2f7;
        border-radius:10px;
        background:#f8fafc;
    }
    .wbk-acc__row{display:flex; gap:10px; align-items:flex-start}
    .wbk-acc__thumb{width:64px;height:64px;object-fit:cover;border-radius:10px;border:1px solid #e5e7eb}
    .wbk-acc__meta{font-size:12px; line-height:1.6; color:#334155}
    .wbk-acc__chev{transition:transform .18s ease}
    .wbk-acc__head[aria-expanded='true'] .wbk-acc__chev{transform:rotate(180deg)}
    ";
    wp_add_inline_style('wp-admin', $css);

    $js = "
    document.addEventListener('click', function(e){
        const btn = e.target.closest('.wbk-acc__head');
        if(!btn) return;
        const panel = btn.parentElement.querySelector('.wbk-acc__panel');
        const expanded = btn.getAttribute('aria-expanded') === 'true';
        btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
        if(panel){
            if(expanded){ panel.hidden = true; }
            else { panel.hidden = false; }
        }
    });
    ";
    wp_add_inline_script('jquery-core', $js);
});


/*--------------------------------------------------------------
4. SLIDER SHORTCODE [wbk_slider1] - FIXED LAYERING (CPT SOURCE)
--------------------------------------------------------------*/
function wbk_slider1_shortcode() {

    $q = new WP_Query([
        'post_type'      => 'wbk_slider1',
        'post_status'    => 'publish',
        'posts_per_page' => 50,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'  => true,
    ]);

    if ( ! $q->have_posts() ) return '';

    ob_start();
    ?>
    <div class="swiper wbk-tour-hero-swiper">
        <div class="swiper-wrapper">

            <?php while ( $q->have_posts() ) : $q->the_post(); ?>
                <?php
                    $img = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : '';
                    if ( ! $img ) continue;

                    $desc     = (string) get_post_meta(get_the_ID(), '_wbk_slider1_desc', true);
                    $btn_text = (string) get_post_meta(get_the_ID(), '_wbk_slider1_btn_text', true);
                    $btn_link = (string) get_post_meta(get_the_ID(), '_wbk_slider1_btn_link', true);
                ?>

                <div class="swiper-slide wbk-swiper-slide" style="display: flex; align-items: center; justify-content: center;">

                    <div class="wbk-slide-bg" style="background-image:url('<?php echo esc_url($img); ?>'); position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;"></div>

                    <div class="wbk-slide-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 2; background: linear-gradient(to right, rgba(0,46,99,0.85), rgba(0,46,99,0.3));"></div>

                    <div class="wbk-slide-content" style="position: relative; z-index: 5; color: #ffffff; width: 100%; padding: 0 50px; text-align: left;">

                        <h2>
                            <?php echo wp_kses_post( get_the_title() ); ?>
                        </h2>

                        <p style="color: #ffffff !important; font-size: 1.2rem; margin-bottom: 10px; opacity: 1;">
                            <?php echo wp_kses_post( $desc ); ?>
                        </p>

                        <?php if ( ! empty($btn_text) ) : ?>
                            <a href="<?php echo esc_url($btn_link); ?>"
                               class="wbk-hero-cta">
                                <?php echo esc_html($btn_text); ?>
                            </a>
                        <?php endif; ?>

                    </div>
                </div>

            <?php endwhile; wp_reset_postdata(); ?>

        </div>
        <div class="swiper-pagination wbk-swiper-pagination"></div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'wbk_slider1', 'wbk_slider1_shortcode' );
