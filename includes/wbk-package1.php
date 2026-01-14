<?php
if ( ! defined('ABSPATH') ) exit;

/**
 * Packages 1
 * CPT: wbk_package1
 * Shortcode: [wbk_package1]
 * Single page template output via template_redirect
 *
 * CSS:
 * - assets/css/package1.css
 * - assets/css/package1-single.css
 */

/*--------------------------------------------------------------
0) CPT (submenu under WEBKIH Kit)
--------------------------------------------------------------*/
add_action('init', function () {

    $labels = [
        'name'               => __('Packages 1', 'webkih-site-builder-kit'),
        'singular_name'      => __('Package', 'webkih-site-builder-kit'),
        'menu_name'          => __('Packages 1', 'webkih-site-builder-kit'),
        'add_new'            => __('Add New', 'webkih-site-builder-kit'),
        'add_new_item'       => __('Add New Package', 'webkih-site-builder-kit'),
        'edit_item'          => __('Edit Package', 'webkih-site-builder-kit'),
        'new_item'           => __('New Package', 'webkih-site-builder-kit'),
        'view_item'          => __('View Package', 'webkih-site-builder-kit'),
        'all_items'          => __('Packages 1', 'webkih-site-builder-kit'),
        'search_items'       => __('Search Packages', 'webkih-site-builder-kit'),
        'not_found'          => __('No packages found.', 'webkih-site-builder-kit'),
        'not_found_in_trash' => __('No packages found in Trash.', 'webkih-site-builder-kit'),
    ];

    register_post_type('wbk_package1', [
        'labels'              => $labels,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => 'wbk-dashboard', // ✅ under WEBKIH Kit
        'menu_icon'           => 'dashicons-palmtree',
        'supports'            => ['title', 'editor', 'thumbnail'],
        'has_archive'         => false,
        'rewrite'             => ['slug' => 'wbk_package'],
        'show_in_rest'        => true,
        'capability_type'     => 'post',
    ]);
});


/*--------------------------------------------------------------
1) ENQUEUE CSS (grid + single)
--------------------------------------------------------------*/
add_action('wp_enqueue_scripts', function () {

    // Single page CSS
    if ( is_singular('wbk_package1') ) {
        wp_enqueue_style('wbk-package1-single', WBK_URL . 'assets/css/package1-single.css', [], WBK_VER);
        return;
    }

    // Grid CSS only when shortcode exists in current post content
    if ( is_singular() ) {
        global $post;
        if ( $post instanceof WP_Post && has_shortcode($post->post_content, 'wbk_package1') ) {
            wp_enqueue_style('wbk-package1', WBK_URL . 'assets/css/package1.css', [], WBK_VER);
        }
    }
});


/*--------------------------------------------------------------
2) META BOX (Duration, Price, Destinations, Book Button)
--------------------------------------------------------------*/
add_action('add_meta_boxes', function () {
    add_meta_box(
        'wbk_package1_meta',
        __('Package Details', 'webkih-site-builder-kit'),
        'wbk_package1_meta_cb',
        'wbk_package1',
        'normal',
        'high'
    );
});

function wbk_package1_meta_cb($post) {

    $duration     = get_post_meta($post->ID, '_wbk_pkg1_duration', true);
    $price        = get_post_meta($post->ID, '_wbk_pkg1_price', true);
    $destinations = get_post_meta($post->ID, '_wbk_pkg1_destinations', true);
    $book_text    = get_post_meta($post->ID, '_wbk_pkg1_book_text', true);
    $book_url     = get_post_meta($post->ID, '_wbk_pkg1_book_url', true);

    wp_nonce_field('wbk_pkg1_save_meta', 'wbk_pkg1_nonce');
    ?>
    <table class="form-table" role="presentation">
        <tr>
            <th><label for="wbk_pkg1_duration"><?php esc_html_e('Duration Badge', 'webkih-site-builder-kit'); ?></label></th>
            <td>
                <input type="text" class="regular-text" id="wbk_pkg1_duration" name="wbk_pkg1_duration"
                       value="<?php echo esc_attr($duration); ?>"
                       placeholder="5 Days / 4 Nights">
            </td>
        </tr>

        <tr>
            <th><label for="wbk_pkg1_price"><?php esc_html_e('Price (Text)', 'webkih-site-builder-kit'); ?></label></th>
            <td>
                <input type="text" class="regular-text" id="wbk_pkg1_price" name="wbk_pkg1_price"
                       value="<?php echo esc_attr($price); ?>"
                       placeholder="BDT 45,500">
                <p class="description"><?php esc_html_e('Keep it as text so you can use BDT/USD easily.', 'webkih-site-builder-kit'); ?></p>
            </td>
        </tr>

        <tr>
            <th><label for="wbk_pkg1_destinations"><?php esc_html_e('Destinations', 'webkih-site-builder-kit'); ?></label></th>
            <td>
                <input type="text" class="regular-text" id="wbk_pkg1_destinations" name="wbk_pkg1_destinations"
                       value="<?php echo esc_attr($destinations); ?>"
                       placeholder="Bangkok, Pattaya">
            </td>
        </tr>

        <tr>
            <th><label for="wbk_pkg1_book_text"><?php esc_html_e('Book Button Text', 'webkih-site-builder-kit'); ?></label></th>
            <td>
                <input type="text" class="regular-text" id="wbk_pkg1_book_text" name="wbk_pkg1_book_text"
                       value="<?php echo esc_attr($book_text); ?>"
                       placeholder="Book This Trip">
            </td>
        </tr>

        <tr>
            <th><label for="wbk_pkg1_book_url"><?php esc_html_e('Book Button URL', 'webkih-site-builder-kit'); ?></label></th>
            <td>
                <input type="url" class="large-text" id="wbk_pkg1_book_url" name="wbk_pkg1_book_url"
                       value="<?php echo esc_attr($book_url); ?>"
                       placeholder="https://">
            </td>
        </tr>
    </table>

    <p><strong><?php esc_html_e('Hero/Image:', 'webkih-site-builder-kit'); ?></strong> <?php esc_html_e('Use Featured Image.', 'webkih-site-builder-kit'); ?></p>
    <?php
}

add_action('save_post_wbk_package1', function ($post_id) {

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can('edit_post', $post_id) ) return;

    if ( ! isset($_POST['wbk_pkg1_nonce']) || ! wp_verify_nonce($_POST['wbk_pkg1_nonce'], 'wbk_pkg1_save_meta') ) {
        return;
    }

    $duration     = isset($_POST['wbk_pkg1_duration']) ? sanitize_text_field( wp_unslash($_POST['wbk_pkg1_duration']) ) : '';
    $price        = isset($_POST['wbk_pkg1_price']) ? sanitize_text_field( wp_unslash($_POST['wbk_pkg1_price']) ) : '';
    $destinations = isset($_POST['wbk_pkg1_destinations']) ? sanitize_text_field( wp_unslash($_POST['wbk_pkg1_destinations']) ) : '';
    $book_text    = isset($_POST['wbk_pkg1_book_text']) ? sanitize_text_field( wp_unslash($_POST['wbk_pkg1_book_text']) ) : '';
    $book_url     = isset($_POST['wbk_pkg1_book_url']) ? esc_url_raw( wp_unslash($_POST['wbk_pkg1_book_url']) ) : '';

    update_post_meta($post_id, '_wbk_pkg1_duration', $duration);
    update_post_meta($post_id, '_wbk_pkg1_price', $price);
    update_post_meta($post_id, '_wbk_pkg1_destinations', $destinations);
    update_post_meta($post_id, '_wbk_pkg1_book_text', $book_text);
    update_post_meta($post_id, '_wbk_pkg1_book_url', $book_url);
});


/*--------------------------------------------------------------
3) SHORTCODE: [wbk_package1] (GRID)
--------------------------------------------------------------*/
add_shortcode('wbk_package1', function () {

    // Make sure CSS loads when shortcode runs (fallback)
    wp_enqueue_style('wbk-package1', WBK_URL . 'assets/css/package1.css', [], WBK_VER);

    $q = new WP_Query([
        'post_type'      => 'wbk_package1',
        'post_status'    => 'publish',
        'posts_per_page' => 50,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'  => true,
    ]);

    if ( ! $q->have_posts() ) return '';

    ob_start();
    ?>
    <div class="wbk-package1-section">
        <h2 style="text-align:center; color:#002e63; margin-bottom:30px;">Featured Packages</h2>

        <div class="wbk-package1-grid">
            <?php while ( $q->have_posts() ) : $q->the_post(); ?>
                <?php
                    $id   = get_the_ID();
                    $img  = has_post_thumbnail($id) ? get_the_post_thumbnail_url($id, 'large') : '';
                    $dur  = (string) get_post_meta($id, '_wbk_pkg1_duration', true);
                    $price = (string) get_post_meta($id, '_wbk_pkg1_price', true);
                    $url  = get_permalink($id);

                    if ( ! $img ) {
                        // If no featured image, skip (your design depends on background-image)
                        continue;
                    }
                ?>

                <div class="wbk-package1-card">
                    <div class="wbk-package1-img-box" style="background-image:url('<?php echo esc_url($img); ?>')">
                        <?php if ( $dur !== '' ) : ?>
                            <span class="wbk-package1-badge"><?php echo esc_html($dur); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="wbk-package1-info">
                        <h3><?php echo esc_html( get_the_title($id) ); ?></h3>

                        <div class="wbk-package1-footer">
                            <?php if ( $price !== '' ) : ?>
                                <span class="wbk-package1-price"><?php echo esc_html($price); ?></span>
                            <?php endif; ?>

                            <a href="<?php echo esc_url($url); ?>" class="wbk-package1-btn">
                                <?php esc_html_e('Details', 'webkih-site-builder-kit'); ?>
                            </a>
                        </div>
                    </div>
                </div>

            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
});


/*--------------------------------------------------------------
4) SINGLE PAGE TEMPLATE OVERRIDE
--------------------------------------------------------------*/
add_filter('template_include', function ($template) {
    if ( is_singular('wbk_package1') ) {
        $candidate = WBK_DIR . 'templates/single-wbk_package1.php';
        if ( file_exists($candidate) ) {
            return $candidate;
        }
    }

    return $template;
});
