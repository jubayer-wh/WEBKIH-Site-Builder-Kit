<?php
if ( ! defined('ABSPATH') ) exit;

/**
 * WBK Slider 2 (Simple) – CPT based (namespaced to avoid Slider 1 conflicts)
 * File: includes/wbk-slider2.php
 * Assets:
 *  - assets/css/slider2.css
 *  - assets/js/slider2.js
 *
 * CPT: wbk_slider2
 * Shortcode: [wbk_slider2]
 */

/*--------------------------------------------------------------
1. REGISTER CPT (submenu under WEBKIH Kit)
--------------------------------------------------------------*/
add_action('init', function () {

    $labels = [
        'name'               => 'Slider 2 Slides',
        'singular_name'      => 'Slider 2 Slide',
        'menu_name'          => 'Slider 2',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Slide',
        'edit_item'          => 'Edit Slide',
        'new_item'           => 'New Slide',
        'view_item'          => 'View Slide',
        'all_items'          => 'Sliders 2',
        'search_items'       => 'Search Slides',
        'not_found'          => 'No slides found.',
        'not_found_in_trash' => 'No slides found in Trash.',
    ];

    register_post_type('wbk_slider2', [
        'labels'          => $labels,
        'public'          => false,
        'show_ui'         => true,
        'show_in_menu'    => 'wbk-dashboard', // submenu under WEBKIH Kit
        'menu_icon'       => 'dashicons-images-alt2',
        'supports'        => ['title', 'thumbnail', 'page-attributes'],
        'capability_type' => 'post',
        'show_in_rest'    => true,
    ]);
});


/*--------------------------------------------------------------
2. META BOX (Heading + Description)
--------------------------------------------------------------*/
add_action('add_meta_boxes', function () {
    add_meta_box(
        'wbk_slider2_meta',
        'Slide Content',
        'wbk_slider2_meta_cb',
        'wbk_slider2',
        'normal',
        'high'
    );
});

function wbk_slider2_meta_cb($post) {

    $heading = get_post_meta($post->ID, '_wbk_slider2_heading', true);
    $desc    = get_post_meta($post->ID, '_wbk_slider2_desc', true);

    wp_nonce_field('wbk_slider2_save_meta', 'wbk_slider2_nonce');
    ?>
    <table class="form-table">
        <tr>
            <th><label for="wbk_slider2_heading">Heading (H2)</label></th>
            <td>
                <input type="text" class="regular-text" id="wbk_slider2_heading" name="wbk_slider2_heading"
                       value="<?php echo esc_attr($heading); ?>" placeholder="Modern Design">
            </td>
        </tr>

        <tr>
            <th><label for="wbk_slider2_desc">Description</label></th>
            <td>
                <textarea class="large-text" rows="4" id="wbk_slider2_desc" name="wbk_slider2_desc"
                          placeholder="Built with performance in mind."><?php echo esc_textarea($desc); ?></textarea>
            </td>
        </tr>
    </table>

    <p><strong>Slide Image:</strong> use the <em>Featured Image</em>.</p>
    <?php
}

add_action('save_post_wbk_slider2', function ($post_id) {

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can('edit_post', $post_id) ) return;

    if (
        ! isset($_POST['wbk_slider2_nonce']) ||
        ! wp_verify_nonce($_POST['wbk_slider2_nonce'], 'wbk_slider2_save_meta')
    ) {
        return;
    }

    $heading = isset($_POST['wbk_slider2_heading']) ? sanitize_text_field( wp_unslash($_POST['wbk_slider2_heading']) ) : '';
    $desc    = isset($_POST['wbk_slider2_desc']) ? sanitize_textarea_field( wp_unslash($_POST['wbk_slider2_desc']) ) : '';

    update_post_meta($post_id, '_wbk_slider2_heading', $heading);
    update_post_meta($post_id, '_wbk_slider2_desc', $desc);
});


/*--------------------------------------------------------------
3. ADMIN LIST – ACCORDION PREVIEW
--------------------------------------------------------------*/
add_filter('manage_wbk_slider2_posts_columns', function ($cols) {
    return [
        'cb'          => '<input type="checkbox" />',
        'title'       => 'Slide Title',
        'wbk_preview' => 'Accordion Preview',
        'date'        => 'Date',
    ];
});

add_action('manage_wbk_slider2_posts_custom_column', function ($col, $post_id) {

    if ( $col !== 'wbk_preview' ) return;

    $img     = has_post_thumbnail($post_id) ? get_the_post_thumbnail_url($post_id, 'thumbnail') : '';
    $heading = (string) get_post_meta($post_id, '_wbk_slider2_heading', true);
    $desc    = (string) get_post_meta($post_id, '_wbk_slider2_desc', true);

    $desc = wp_trim_words($desc, 18, '…');
    ?>
    <details style="cursor:pointer;">
        <summary><strong>View</strong></summary>
        <div style="margin-top:10px;display:flex;gap:10px;align-items:flex-start;">
            <?php if ( $img ) : ?>
                <img src="<?php echo esc_url($img); ?>" alt="" width="70" height="70" style="border-radius:10px;object-fit:cover;">
            <?php endif; ?>
            <div style="font-size:12px;line-height:1.6;">
                <div><strong>Heading:</strong> <?php echo esc_html($heading); ?></div>
                <div><strong>Description:</strong> <?php echo esc_html($desc); ?></div>
            </div>
        </div>
    </details>
    <?php

}, 10, 2);


/*--------------------------------------------------------------
4. FRONTEND ASSETS (safe to call from shortcode)
--------------------------------------------------------------*/
function wbk_slider2_enqueue_assets_once() {

    static $done = false;
    if ( $done ) return;
    $done = true;

    wp_enqueue_style(
        'wbk-slider2-css',
        WBK_URL . 'assets/css/slider2.css',
        [],
        WBK_VER
    );

    wp_enqueue_script(
        'wbk-slider2-js',
        WBK_URL . 'assets/js/slider2.js',
        [],
        WBK_VER,
        true
    );
}


/*--------------------------------------------------------------
5. SHORTCODE OUTPUT [wbk_slider2]
   ✅ Namespaced classes to avoid slider1 conflicts
--------------------------------------------------------------*/
add_shortcode('wbk_slider2', function () {

    wbk_slider2_enqueue_assets_once();

    $q = new WP_Query([
        'post_type'      => 'wbk_slider2',
        'post_status'    => 'publish',
        'posts_per_page' => 50,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'no_found_rows'  => true,
    ]);

    if ( ! $q->have_posts() ) return '';

    $slides = [];
    while ( $q->have_posts() ) {
        $q->the_post();

        $img = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'full') : '';
        if ( ! $img ) continue;

        $heading = (string) get_post_meta(get_the_ID(), '_wbk_slider2_heading', true);
        $desc    = (string) get_post_meta(get_the_ID(), '_wbk_slider2_desc', true);

        $slides[] = [
            'img' => $img,
            'h2'  => $heading,
            'p'   => $desc,
            'alt' => get_the_title(),
        ];
    }
    wp_reset_postdata();

    if ( empty($slides) ) return '';

    ob_start();
    ?>
    <div class="wbk-slider2-container" data-wbk-slider2="1">
        <div class="wbk-slider2-viewport">
            <?php foreach ( $slides as $s ) : ?>
                <div class="wbk-slider2-slide">
                    <img class="wbk-slider2-img" src="<?php echo esc_url($s['img']); ?>" alt="<?php echo esc_attr($s['alt']); ?>">
                    <div class="wbk-slider2-slide-content">
                        <h2 class="wbk-slider2-title"><?php echo esc_html($s['h2']); ?></h2>
                        <p class="wbk-slider2-desc"><?php echo esc_html($s['p']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <button class="wbk-slider2-nav wbk-slider2-prev" aria-label="Previous">❮</button>
        <button class="wbk-slider2-nav wbk-slider2-next" aria-label="Next">❯</button>

        <div class="wbk-slider2-dots" aria-label="Slider dots">
            <?php foreach ( $slides as $i => $s ) : ?>
                <span class="wbk-slider2-dot<?php echo $i === 0 ? ' active' : ''; ?>"></span>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
});
